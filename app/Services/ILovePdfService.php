<?php

namespace App\Services;

use Ilovepdf\Ilovepdf;
use Ilovepdf\OfficepdfTask;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ILovePdfService
{
    protected $accounts;
    protected $currentAccountIndex = 0;
    protected $ilovepdf;

    public function __construct()
    {
        $this->accounts = config('ilovepdf.accounts');
        $this->initializeAccount(0);
    }

    /**
     * Initialize iLovePDF dengan akun tertentu
     */
    protected function initializeAccount($index)
    {
        if (!isset($this->accounts[$index])) {
            throw new \Exception('Akun iLovePDF tidak tersedia');
        }

        $account = $this->accounts[$index];
        
        if (empty($account['public_key']) || empty($account['secret_key'])) {
            throw new \Exception('API Key iLovePDF belum dikonfigurasi di .env');
        }

        $this->currentAccountIndex = $index;
        $this->ilovepdf = new Ilovepdf($account['public_key'], $account['secret_key']);
        
        Log::info("iLovePDF: Menggunakan {$account['name']}");
    }

    /**
     * Switch ke akun berikutnya jika ada
     */
    protected function switchToNextAccount()
    {
        $nextIndex = $this->currentAccountIndex + 1;
        
        if ($nextIndex >= count($this->accounts)) {
            throw new \Exception('Semua akun iLovePDF sudah mencapai limit. Silakan coba lagi nanti.');
        }

        Log::warning("iLovePDF: Akun {$this->accounts[$this->currentAccountIndex]['name']} limit, switching ke akun berikutnya...");
        $this->initializeAccount($nextIndex);
    }

    /**
     * Convert Word (DOCX) ke PDF
     * 
     * @param string $wordFilePath - Path lengkap ke file DOCX
     * @param string $outputFileName - Nama file output (tanpa path)
     * @return string - Path ke file PDF yang sudah di-convert
     */
    public function convertWordToPdf($wordFilePath, $outputFileName = null)
    {
        $maxRetries = count($this->accounts);
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                Log::info("iLovePDF: Memulai konversi Word ke PDF (Attempt " . ($attempt + 1) . "/{$maxRetries})");
                Log::info("iLovePDF: File input: {$wordFilePath}");

                // Validasi file exists
                if (!file_exists($wordFilePath)) {
                    throw new \Exception("File Word tidak ditemukan: {$wordFilePath}");
                }

                // Create task
                $task = $this->ilovepdf->newTask('officepdf');

                // Upload file
                Log::info("iLovePDF: Uploading file...");
                $file = $task->addFile($wordFilePath);

                // Execute conversion
                Log::info("iLovePDF: Executing conversion...");
                $task->execute();

                // [MANUAL DOWNLOAD APPROACH]
                // We bypass the library's download() method because it has a bug with 'Content-Disposition' header
                Log::info("iLovePDF: Downloading converted PDF manually to avoid library bug...");
                
                $outputDir = storage_path('app/public');
                if (!file_exists($outputDir)) {
                    mkdir($outputDir, 0755, true);
                }

                $actualFileName = pathinfo($wordFilePath, PATHINFO_FILENAME) . '.pdf';
                $outputPath = $outputDir . DIRECTORY_SEPARATOR . $actualFileName;

                // Build the manual download URL using getters
                // getWorkerServer() returns the full URL including https://
                $workerServer = $task->getWorkerServer();
                $taskId = $task->getTaskId();
                $downloadUrl = $workerServer . "/v1/download/" . $taskId;
                
                Log::info("iLovePDF: Requesting file from: {$downloadUrl}");
                
                // Use Laravel's Http client to fetch the file content
                // Use getJWT() to get the secure token
                $response = \Illuminate\Support\Facades\Http::withToken($task->getJWT())
                    ->timeout(60)
                    ->get($downloadUrl);

                if ($response->successful()) {
                    file_put_contents($outputPath, $response->body());
                    Log::info("iLovePDF: Manual download successful! Saved to: {$outputPath}");
                } else {
                    $errorBody = $response->body();
                    Log::error("iLovePDF Download Failed: " . $errorBody);
                    throw new \Exception("Gagal mengunduh file dari iLovePDF. Status: " . $response->status());
                }

                // Verify file exists
                if (!file_exists($outputPath) || filesize($outputPath) == 0) {
                    throw new \Exception("File PDF tidak terbentuk atau kosong di {$outputPath}.");
                }

                return $outputPath;

            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                Log::error("iLovePDF Error (Attempt " . ($attempt + 1) . "): " . $errorMessage);

                // Cek apakah error karena limit
                if (
                    stripos($errorMessage, 'limit') !== false ||
                    stripos($errorMessage, 'quota') !== false ||
                    stripos($errorMessage, 'exceeded') !== false ||
                    stripos($errorMessage, '429') !== false
                ) {
                    // Coba switch ke akun berikutnya
                    try {
                        $this->switchToNextAccount();
                        $attempt++;
                        continue; // Retry dengan akun baru
                    } catch (\Exception $switchError) {
                        // Tidak ada akun lagi
                        throw $switchError;
                    }
                }

                // Error lain (bukan limit), langsung throw
                throw new \Exception("Gagal convert Word ke PDF: " . $errorMessage);
            }
        }

        throw new \Exception("Gagal convert Word ke PDF setelah {$maxRetries} percobaan");
    }

    /**
     * Get informasi akun yang sedang digunakan
     */
    public function getCurrentAccountInfo()
    {
        return $this->accounts[$this->currentAccountIndex];
    }

    /**
     * Reset ke akun pertama (untuk testing atau reset manual)
     */
    public function resetToFirstAccount()
    {
        $this->initializeAccount(0);
    }
}
