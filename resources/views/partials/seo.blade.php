{{-- SEO Meta Tags Component --}}
@php
    use App\Helpers\SeoHelper;
    $seo = SeoHelper::generateMetaTags($page ?? 'home', $seoData ?? []);
@endphp

<!-- Primary Meta Tags -->
<meta name="title" content="{{ $seo['title'] }}">
<meta name="description" content="{{ $seo['description'] }}">
<meta name="keywords" content="{{ $seo['keywords'] }}">
<meta name="author" content="Lapas Kelas IIB Lamongan">
<meta name="robots" content="index, follow">
<meta name="language" content="Indonesian">
<meta name="revisit-after" content="7 days">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:url" content="{{ $seo['url'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:image" content="{{ $seo['image'] }}">
<meta property="og:site_name" content="{{ $seo['site_name'] }}">
<meta property="og:locale" content="{{ $seo['locale'] }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $seo['url'] }}">
<meta property="twitter:title" content="{{ $seo['title'] }}">
<meta property="twitter:description" content="{{ $seo['description'] }}">
<meta property="twitter:image" content="{{ $seo['image'] }}">

<!-- Additional SEO -->
<link rel="canonical" href="{{ $seo['url'] }}">
<meta name="geo.region" content="ID-JI">
<meta name="geo.placename" content="Lamongan">
<meta name="geo.position" content="-7.1167;112.4167">
<meta name="ICBM" content="-7.1167, 112.4167">

@if(isset($structuredData))
    <!-- Structured Data (Schema.org JSON-LD) -->
    <script type="application/ld+json">
        {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endif
