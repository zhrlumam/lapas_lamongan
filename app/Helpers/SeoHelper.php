<?php

namespace App\Helpers;

class SeoHelper
{
    /**
     * Generate SEO meta tags for a page
     */
    public static function generateMetaTags($page = 'home', $data = [])
    {
        $baseUrl = url('/');
        $siteName = 'SIPAS Lamongan - Lapas Kelas IIB Lamongan';
        $defaultImage = asset('assets/hero.jpg');
        
        $seo = [
            'home' => [
                'title' => 'Lapas Kelas IIB Lamongan - Portal Digital Pemasyarakatan',
                'description' => 'Portal resmi Lembaga Pemasyarakatan Kelas IIB Lamongan. Layanan kunjungan online, pengaduan, integrasi PB/CB/CMB, informasi WBP, dan produk hasil karya warga binaan.',
                'keywords' => 'lapas lamongan, pemasyarakatan lamongan, kunjungan online, pengaduan lapas, warga binaan, ditjenpas, kemenkumham, lapas jawa timur',
                'image' => $defaultImage,
                'url' => $baseUrl,
            ],
            'berita' => [
                'title' => $data['title'] ?? 'Berita & Informasi - Lapas Lamongan',
                'description' => $data['description'] ?? 'Berita terkini dan informasi seputar kegiatan Lembaga Pemasyarakatan Kelas IIB Lamongan.',
                'keywords' => 'berita lapas, informasi pemasyarakatan, kegiatan lapas lamongan',
                'image' => $data['image'] ?? $defaultImage,
                'url' => $data['url'] ?? url('/berita'),
            ],
            'profile' => [
                'title' => 'Profil Instansi - Lapas Kelas IIB Lamongan',
                'description' => 'Profil lengkap Lapas Kelas IIB Lamongan meliputi sejarah, visi misi, struktur organisasi, tugas pokok dan fungsi (Tupoksi), serta informasi kontak.',
                'keywords' => 'profil lapas lamongan, sejarah lapas, visi misi, struktur organisasi, tupoksi',
                'image' => $defaultImage,
                'url' => url('/profile'),
            ],
            'galeri' => [
                'title' => 'Galeri Kegiatan - Lapas Kelas IIB Lamongan',
                'description' => 'Dokumentasi foto kegiatan pembinaan, pelatihan, dan aktivitas warga binaan di Lembaga Pemasyarakatan Kelas IIB Lamongan.',
                'keywords' => 'galeri lapas, foto kegiatan, dokumentasi lapas lamongan',
                'image' => $data['image'] ?? $defaultImage,
                'url' => url('/galeri'),
            ],
            'produk' => [
                'title' => 'Produk WBP - Karya Warga Binaan Lapas Lamongan',
                'description' => 'Katalog produk hasil karya warga binaan pemasyarakatan Lapas Lamongan. Furniture, kerajinan tangan, dan produk berkualitas lainnya.',
                'keywords' => 'produk wbp, kerajinan lapas, furniture lapas, karya warga binaan',
                'image' => $data['image'] ?? $defaultImage,
                'url' => url('/produk'),
            ],
            'kunjungan' => [
                'title' => 'Pendaftaran Kunjungan Online - Lapas Lamongan',
                'description' => 'Daftar kunjungan tatap muka secara online untuk keluarga warga binaan. Mudah, cepat, dan terpercaya.',
                'keywords' => 'kunjungan online, daftar kunjungan lapas, jadwal kunjungan, keluarga wbp',
                'image' => $defaultImage,
                'url' => url('/kunjungan'),
            ],
            'pengaduan' => [
                'title' => 'WBS Pengaduan - Whistleblowing System Lapas Lamongan',
                'description' => 'Layanan pengaduan masyarakat (WBS) untuk melaporkan masalah, keluhan, atau saran terkait pelayanan Lapas Kelas IIB Lamongan.',
                'keywords' => 'pengaduan lapas, wbs, whistleblowing, lapor masalah, keluhan pelayanan',
                'image' => $defaultImage,
                'url' => url('/pengaduan'),
            ],
            'integrasi' => [
                'title' => 'Layanan Integrasi - PB, CB, CMB Lapas Lamongan',
                'description' => 'Portal layanan integrasi untuk Pembebasan Bersyarat (PB), Cuti Bersyarat (CB), dan Cuti Menjelang Bebas (CMB).',
                'keywords' => 'pembebasan bersyarat, cuti bersyarat, cmb, layanan integrasi, asimilasi',
                'image' => $defaultImage,
                'url' => url('/integrasi'),
            ],
        ];

        $meta = $seo[$page] ?? $seo['home'];
        
        return [
            'title' => $meta['title'],
            'description' => $meta['description'],
            'keywords' => $meta['keywords'],
            'image' => $meta['image'],
            'url' => $meta['url'],
            'site_name' => $siteName,
            'type' => $page === 'berita' && isset($data['title']) ? 'article' : 'website',
            'locale' => 'id_ID',
        ];
    }

    /**
     * Generate structured data (Schema.org JSON-LD)
     */
    public static function generateStructuredData($type = 'organization', $data = [])
    {
        $schemas = [
            'organization' => [
                '@context' => 'https://schema.org',
                '@type' => 'GovernmentOrganization',
                'name' => 'Lembaga Pemasyarakatan Kelas IIB Lamongan',
                'alternateName' => 'Lapas Lamongan',
                'url' => url('/'),
                'logo' => asset('assets/logolap.png'),
                'description' => 'Lembaga Pemasyarakatan Kelas IIB Lamongan di bawah Direktorat Jenderal Pemasyarakatan, Kementerian Hukum dan HAM Republik Indonesia.',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Jl. Sumargo No. 42',
                    'addressLocality' => 'Lamongan',
                    'addressRegion' => 'Jawa Timur',
                    'postalCode' => '62200',
                    'addressCountry' => 'ID',
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+62-322-321124',
                    'contactType' => 'Customer Service',
                    'email' => 'lapas.lamongan@gmail.com',
                    'availableLanguage' => 'Indonesian',
                ],
                'sameAs' => [
                    'https://www.facebook.com/lapaslamongan',
                    'https://www.instagram.com/lapaslamongan',
                    'https://twitter.com/lapaslamongan',
                ],
            ],
            'article' => [
                '@context' => 'https://schema.org',
                '@type' => 'NewsArticle',
                'headline' => $data['title'] ?? '',
                'image' => $data['image'] ?? asset('assets/hero.jpg'),
                'datePublished' => $data['date'] ?? now()->toIso8601String(),
                'dateModified' => $data['updated'] ?? now()->toIso8601String(),
                'author' => [
                    '@type' => 'Organization',
                    'name' => 'Lapas Kelas IIB Lamongan',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Lapas Kelas IIB Lamongan',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('assets/logolap.png'),
                    ],
                ],
                'description' => $data['description'] ?? '',
            ],
            'breadcrumb' => [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $data['items'] ?? [],
            ],
        ];

        return $schemas[$type] ?? $schemas['organization'];
    }
}
