<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    private $backupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        
        // Create backup directory if not exists
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    public function index()
    {
        $backups = $this->getBackupFiles();
        return view('admin.backup.index', compact('backups'));
    }

    public function create()
    {
        try {
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $filepath = $this->backupPath . '/' . $filename;
            
            // Detect mysqldump path for Windows/XAMPP
            $mysqldumpPath = $this->getMysqldumpPath();
            
            if (!$mysqldumpPath) {
                throw new \Exception('mysqldump tidak ditemukan. Pastikan MySQL/XAMPP terinstall dengan benar.');
            }
            
            // Build mysqldump command for Windows
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s --skip-comments --skip-extended-insert %s > "%s" 2>&1',
                $mysqldumpPath,
                $username,
                $password,
                $host,
                $database,
                $filepath
            );
            
            // Execute backup
            exec($command, $output, $returnVar);
            
            if ($returnVar === 0 && file_exists($filepath) && filesize($filepath) > 0) {
                Log::info('Database backup created successfully: ' . $filename);
                return redirect()->route('admin.backup.index')
                    ->with('success', 'Backup database berhasil dibuat: ' . $filename);
            } else {
                // Log error details
                $errorMsg = 'Backup gagal. Return code: ' . $returnVar;
                if (!empty($output)) {
                    $errorMsg .= ' | Output: ' . implode(' ', $output);
                }
                Log::error($errorMsg);
                
                // Delete failed backup file if exists
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
                
                throw new \Exception($errorMsg);
            }
            
        } catch (\Exception $e) {
            Log::error('Backup error: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }
    
    /**
     * Detect mysqldump path on Windows/XAMPP
     */
    private function getMysqldumpPath()
    {
        // Common XAMPP paths
        $possiblePaths = [
            'C:/xampp/mysql/bin/mysqldump.exe',
            'C:/XAMPP/mysql/bin/mysqldump.exe',
            'D:/xampp/mysql/bin/mysqldump.exe',
            'E:/xampp/mysql/bin/mysqldump.exe',
            '/usr/bin/mysqldump', // Linux
            '/usr/local/bin/mysqldump', // Mac
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        // Try to find mysqldump in PATH
        exec('where mysqldump 2>nul', $output, $returnVar);
        if ($returnVar === 0 && !empty($output[0])) {
            return trim($output[0]);
        }
        
        return null;
    }

    public function download($filename)
    {
        try {
            $filepath = $this->backupPath . '/' . $filename;
            
            if (!file_exists($filepath)) {
                return redirect()->route('admin.backup.index')
                    ->with('error', 'File backup tidak ditemukan.');
            }
            
            return response()->download($filepath);
            
        } catch (\Exception $e) {
            Log::error('Download backup error: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Gagal mengunduh backup: ' . $e->getMessage());
        }
    }

    public function destroy($filename)
    {
        try {
            $filepath = $this->backupPath . '/' . $filename;
            
            if (file_exists($filepath)) {
                unlink($filepath);
                Log::info('Backup deleted: ' . $filename);
                return redirect()->route('admin.backup.index')
                    ->with('success', 'Backup berhasil dihapus.');
            }
            
            return redirect()->route('admin.backup.index')
                ->with('error', 'File backup tidak ditemukan.');
                
        } catch (\Exception $e) {
            Log::error('Delete backup error: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Gagal menghapus backup: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string'
        ]);

        try {
            $filename = $request->backup_file;
            $filepath = $this->backupPath . '/' . $filename;
            
            if (!file_exists($filepath)) {
                return redirect()->route('admin.backup.index')
                    ->with('error', 'File backup tidak ditemukan.');
            }
            
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            
            // Detect mysql path for Windows/XAMPP
            $mysqlPath = $this->getMysqlPath();
            
            if (!$mysqlPath) {
                throw new \Exception('mysql tidak ditemukan. Pastikan MySQL/XAMPP terinstall dengan benar.');
            }
            
            // Build mysql restore command for Windows
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s %s < "%s" 2>&1',
                $mysqlPath,
                $username,
                $password,
                $host,
                $database,
                $filepath
            );
            
            // Execute restore
            exec($command, $output, $returnVar);
            
            if ($returnVar === 0) {
                Log::info('Database restored successfully from: ' . $filename);
                return redirect()->route('admin.backup.index')
                    ->with('success', 'Database berhasil di-restore dari: ' . $filename);
            } else {
                $errorMsg = 'Restore gagal. Return code: ' . $returnVar;
                if (!empty($output)) {
                    $errorMsg .= ' | Output: ' . implode(' ', $output);
                }
                Log::error($errorMsg);
                throw new \Exception($errorMsg);
            }
            
        } catch (\Exception $e) {
            Log::error('Restore error: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Gagal restore database: ' . $e->getMessage());
        }
    }
    
    /**
     * Detect mysql path on Windows/XAMPP
     */
    private function getMysqlPath()
    {
        // Common XAMPP paths
        $possiblePaths = [
            'C:/xampp/mysql/bin/mysql.exe',
            'C:/XAMPP/mysql/bin/mysql.exe',
            'D:/xampp/mysql/bin/mysql.exe',
            'E:/xampp/mysql/bin/mysql.exe',
            '/usr/bin/mysql', // Linux
            '/usr/local/bin/mysql', // Mac
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        // Try to find mysql in PATH
        exec('where mysql 2>nul', $output, $returnVar);
        if ($returnVar === 0 && !empty($output[0])) {
            return trim($output[0]);
        }
        
        return null;
    }

    private function getBackupFiles()
    {
        $files = File::files($this->backupPath);
        $backups = [];
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'date' => date('d M Y H:i:s', $file->getMTime()),
                    'timestamp' => $file->getMTime()
                ];
            }
        }
        
        // Sort by timestamp descending (newest first)
        usort($backups, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        return $backups;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
