# OWASP Top 10 Mapping — Zen Porto

**Tanggal:** 2026-10-01  
**Scope:** static review terhadap source code dan temuan audit sebelumnya; bukan penetration test, deployment review, atau sertifikasi compliance.

## Kesimpulan singkat

Ya, temuan project ini dapat dikaitkan dengan OWASP Top 10. Namun, tidak semua temuan adalah vulnerability OWASP secara langsung. OWASP Top 10 adalah dokumen awareness dan titik awal, bukan checklist lengkap atau bukti bahwa seluruh kategori telah diaudit. Versi resmi terbaru adalah **OWASP Top 10:2025**; beberapa temuan audit sebelumnya memakai istilah Top 10:2021, sehingga nama kategorinya berubah.

Temuan yang paling kuat kaitannya:

1. **Riwayat `.env` di Git** → A02:2025 Security Misconfiguration, dan A04:2025 Cryptographic Failures bila berisi key/credential yang masih valid.
2. **`{!! !!}` pada data project dan `innerHTML` untuk translasi** → A05:2025 Injection (XSS), tetapi exploitability bergantung pada apakah penyerang dapat memengaruhi data yang dirender.
3. **Tidak ada rate limit eksplisit, batas `message`, dan email dikirim synchronous** → A06:2025 Insecure Design, terutama missing resource/abuse controls. Ini juga berkontribusi pada risiko resilience/DoS, yang dalam Top 10:2025 tidak menjadi kategori tersendiri.
4. **CDN third-party tanpa pin versi/SRI dan URL `@latest`** → A03:2025 Software Supply Chain Failures dan A08:2025 Software or Data Integrity Failures; ketiadaan security headers seperti CSP → A02:2025.

## Versi OWASP yang dipakai

Mapping utama menggunakan [OWASP Top 10:2025](https://top10.owasp.org/2025/). OWASP menjelaskan bahwa 2025 mengubah nomenklatur dan scope, antara lain:

- 2021 A05 Security Misconfiguration menjadi **2025 A02 Security Misconfiguration**.
- 2021 A06 Vulnerable and Outdated Components diperluas menjadi **2025 A03 Software Supply Chain Failures**.
- 2021 A03 Injection menjadi **2025 A05 Injection**.
- 2021 A04 Insecure Design menjadi **2025 A06 Insecure Design**.
- SSRF yang sebelumnya A10:2021 digabung ke A01:2025 Broken Access Control.

Rujukan perubahan tersebut ada di [OWASP Top 10:2025 Introduction](https://top10.owasp.org/2025/0x00_2025-Introduction/) dan daftar kategori resmi di [OWASP Top 10:2025](https://top10.owasp.org/2025/).

## Evidence anchors di repository

- Public API dan query tanpa pagination: [routes/api.php](../routes/api.php#L14) dan [routes/web.php](../routes/web.php#L9).
- Contact validation dan synchronous mail: [ContactController.php](../app/Http/Controllers/ContactController.php#L10).
- Raw project output/data attributes: [home.blade.php](../resources/views/pages/home.blade.php#L186) dan [show.blade.php](../resources/views/pages/project/show.blade.php#L76).
- DOM sink `innerHTML`: [alpine-init.js](../resources/js/alpine-init.js#L11).
- Unpinned/duplicate third-party scripts: [welcome.blade.php](../resources/views/layout/welcome.blade.php#L21) dan [welcome.blade.php](../resources/views/layout/welcome.blade.php#L47).
- Dependency declarations: [composer.json](../composer.json) dan [package.json](../package.json).
- `.env` history exposure: Git history menunjukkan commit `6e63dd76` sampai `a819ff1c` menyertakan `.env`, lalu `5782974c` menghapusnya dari tracking. Isi secret tidak disalin ke catatan ini.

## Mapping temuan

| Temuan audit | Mapping 2025 | Status relevansi | Penilaian dan caveat |
|---|---|---|---|
| `.env` pernah committed di Git history | **A02 Security Misconfiguration**; sekunder **A04 Cryptographic Failures** | **Relevan — confirmed condition** | `git log` menunjukkan `.env` pernah ada pada beberapa commit dan baru dihapus pada commit `5782974c`. A02 secara eksplisit mencakup exposure melalui environment variables. A04 berlaku bila key/credential yang terekspos merupakan kunci kriptografis atau secret yang masih dapat dipakai. Isi secret dan validitasnya tidak dinilai ulang dalam note ini. |
| API `GET /api/projects` dan `POST /api/contact` tidak memiliki limiter eksplisit | **A06 Insecure Design**; A01 hanya conditional | **Relevan untuk abuse/resource management; bukan otomatis broken access control** | Endpoint read publik dapat memang dirancang publik. OWASP A01 membedakan public resources dari resource yang harus deny-by-default. `POST /api/contact` menjadi A01 hanya bila seharusnya dibatasi ke caller/role tertentu; tanpa itu, isu utamanya adalah spam, quota, dan availability. Route saat ini tidak tampak memiliki limiter eksplisit; rate limit di reverse proxy/CDN belum diverifikasi. |
| Blade raw output pada field project dan `innerHTML` untuk nilai `data-i18n-*` | **A05 Injection (XSS)** | **Relevan — implementation risk, exploitability conditional** | OWASP 2025 A05 memasukkan XSS. Risiko meningkat bila `title`, `category`, translation, atau data database dapat diubah oleh user/admin/third party. Saat ini data terlihat berasal dari seeder, sehingga review ini belum membuktikan stored-XSS yang exploitable. Sink yang perlu ditinjau: [home.blade.php](../resources/views/pages/home.blade.php#L186), [show.blade.php](../resources/views/pages/project/show.blade.php#L76), dan [alpine-init.js](../resources/js/alpine-init.js#L15). |
| Contact form tidak memberi `max` pada `message`; request dapat mengirim email synchronous; tidak ada dedup/rate limit efektif | **A06 Insecure Design**; terkait resilience/DoS | **Relevan** | Ini adalah missing control design: tidak ada batas resource, abuse prevention, dan isolation antara request web dengan SMTP. Validasi field saja bukan rate limiting. OWASP A06 mencantumkan pembatasan konsumsi resource per user/service sebagai kontrol pencegahan. OWASP 2025 menempatkan “Lack of Application Resilience” di luar Top 10 utama, tetapi tetap menyebutnya sebagai risiko yang layak ditangani. |
| Runtime production dan `.env.example` tidak konsisten | **A02 Security Misconfiguration** | **Relevan sebagai configuration-drift risk; belum terbukti vulnerability** | Perbedaan SQLite/file runtime dengan template database/session/cache/queue berbasis database bukan otomatis celah. Menjadi A02 bila deployment menyalin default yang tidak aman, mengaktifkan debug, salah mengatur cookie/TLS/header, atau membuat secret/permission terbuka. Server/proxy production tidak diaudit ulang di sini. |
| CDN Bootstrap/Popper dan Lottie third-party; `@latest`; tidak tampak SRI; tidak tampak CSP di application code | **A03 Software Supply Chain Failures**, **A08 Software or Data Integrity Failures**, dan **A02 Security Misconfiguration** untuk header | **Relevan — hardening/supply-chain gap; bukan bukti component CVE** | OWASP A03 mencakup pelacakan versi, dependency yang obsolete/vulnerable, dan monitoring supply chain. A08 secara eksplisit memberi contoh dependency/CDN yang diperlakukan sebagai trusted tanpa integrity verification. Tidak adanya CSP adalah hardening gap; konfigurasi header di web server/proxy belum diverifikasi. |
| Pint/lint gagal dan Sass mengeluarkan deprecation warnings | Tidak ada mapping Top 10 langsung | **Tidak relevan sebagai vulnerability OWASP berdasarkan bukti saat ini** | Ini adalah code-quality/maintainability debt. Baru dapat dikaitkan ke A03 bila deprecation menunjukkan komponen yang unsupported/unmaintained atau dependency rentan; warning saja tidak membuktikan itu. |
| `Project::all()` untuk homepage/API tanpa pagination | Tidak ada mapping Top 10 langsung; **A06 conditional** | **Tidak relevan pada skala saat ini; risiko desain bila data tumbuh** | Dengan dataset kecil, ini bukan broken access control atau injection. Jika data/API menjadi besar atau query dapat dipicu masif, ia dapat menjadi uncontrolled resource consumption/availability concern dan perlu ditangani sebagai A06/resilience. |

## Kategori yang tidak terbukti dari temuan ini

- **A01:2025 Broken Access Control:** tidak ada bukti endpoint admin/private atau record ownership yang dapat diakses tanpa izin. Public API bukan pelanggaran bila memang kontraknya public. Tetap uji authorization bila nanti ada CMS/admin.
- **A07:2025 Authentication Failures:** project yang direview tidak memperlihatkan login atau account-recovery flow yang menjadi temuan audit. Sanctum terpasang bukan berarti ada vulnerability authentication.
- **A09:2025 Security Logging & Alerting Failures:** audit sebelumnya belum membuktikan bahwa log/alert hilang atau tidak dimonitor. Ini menjadi follow-up operational review, terutama untuk spam, validation failures, 429, SMTP failure, dan secret-use events.
- **A10:2025 Mishandling of Exceptional Conditions:** tidak ada temuan spesifik tentang fail-open, rollback, atau error-path yang salah dari daftar audit sebelumnya.
- **SSRF sebagai kategori terpisah:** pada 2025 SSRF masuk A01. Link `live_demo_url` yang hanya dirender sebagai anchor bukan server-side fetch; tidak ada bukti SSRF dari code yang direview.

## Perbaikan yang disarankan berdasarkan OWASP

### Prioritas 0 — tangani secret exposure

1. Perlakukan secret yang pernah ada di history sebagai potentially compromised: rotasi/revoke `APP_KEY`, database password, SMTP credential, cloud credential, dan token lain sesuai dampak. Rotasi `APP_KEY` perlu direncanakan karena dapat menginvalidasi session/cookie dan data terenkripsi.
2. Hapus secret dari repository saat ini dan history sesuai prosedur incident response; tambahkan secret scanning pada pre-commit/CI.
3. Simpan secret pada secret manager atau mekanisme deployment secret yang access-controlled, least-privilege, auditable, dan dapat dirotasi. Lihat [OWASP Secrets Management Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Secrets_Management_Cheat_Sheet.html) dan [A04:2025 Cryptographic Failures](https://top10.owasp.org/2025/A04_2025-Cryptographic_Failures/).

### Prioritas 1 — tutup jalur XSS

1. Gunakan output escaping default Blade untuk data project; hindari `{!! !!}` untuk nilai database atau translation yang tidak benar-benar membutuhkan HTML.
2. Untuk pergantian bahasa, gunakan `textContent` untuk teks biasa. Jika markup memang requirement, gunakan allowlist sanitizer dan pisahkan field “trusted HTML” dari plain text.
3. Tambahkan CSP sebagai defense-in-depth, bukan sebagai pengganti output encoding. Pin sumber script ke domain yang diperlukan; pertimbangkan nonce/hash untuk inline script.
4. Tambahkan test yang memasukkan payload XSS pada field project/translation dan lakukan DAST/browser verification. Rujuk [OWASP Cross Site Scripting Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html), [DOM-based XSS Prevention](https://cheatsheetseries.owasp.org/cheatsheets/DOM_based_XSS_Prevention_Cheat_Sheet.html), dan [A05:2025 Injection](https://top10.owasp.org/2025/A05_2025-Injection/).

### Prioritas 1 — batasi abuse pada contact/API

1. Tambahkan batas `max` untuk `message` dan batas ukuran request di web server/proxy.
2. Terapkan rate limiter per IP dan, bila sesuai, per email/endpoint. Kembalikan `429 Too Many Requests` secara generik; monitor dan sesuaikan threshold dengan traffic normal.
3. Gunakan queue/job untuk pengiriman email, dengan timeout, retry limit, dan failure handling yang jelas agar SMTP tidak memblokir request web.
4. Tambahkan honeypot/CAPTCHA hanya bila threat model dan traffic membutuhkannya; jangan menjadikannya satu-satunya kontrol.
5. Untuk API list, tambahkan pagination, ordering eksplisit, field allowlist/API Resource, caching yang aman, dan quota sesuai kebutuhan. Jika endpoint contact memang private, tambahkan authentication/authorization; jika memang public, dokumentasikan kontraknya dan gunakan abuse controls.
6. Rujuk [OWASP A06:2025 Insecure Design](https://top10.owasp.org/2025/A06_2025-Insecure_Design/) dan [OWASP Denial of Service Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Denial_of_Service_Cheat_Sheet.html).

### Prioritas 2 — hardening konfigurasi dan supply chain

1. Pisahkan template development dan production secara eksplisit; jangan menjadikan `.env.example` sebagai konfigurasi production siap pakai. Tambahkan deployment check untuk `APP_DEBUG=false`, TLS, cookie flags, trusted hosts, permission, mail recipient, dan security headers.
2. Ganti URL `@latest` dengan versi immutable; pilih satu sumber Bootstrap/Popper, atau self-host asset yang diperlukan.
3. Gunakan SRI untuk script CDN yang tetap dipertahankan, dengan `crossorigin="anonymous"`, dan pantau perubahan vendor. Pertimbangkan self-hosting untuk mengurangi supply-chain/runtime drift.
4. Inventaris dan scan dependency PHP/npm termasuk transitive dependency; tambahkan SBOM/dependency scanning di CI. Audit sebelumnya tidak dapat menyimpulkan CVE karena akses registry terbatas, jadi tidak boleh menyatakan dependency aman hanya berdasarkan build yang berhasil.
5. Rujuk [A02:2025 Security Misconfiguration](https://top10.owasp.org/2025/A02_2025-Security_Misconfiguration/), [A03:2025 Software Supply Chain Failures](https://top10.owasp.org/2025/A03_2025-Software_Supply_Chain_Failures/), [A08:2025 Software or Data Integrity Failures](https://top10.owasp.org/2025/A08_2025-Software_or_Data_Integrity_Failures/), [OWASP Content Security Policy Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Content_Security_Policy_Cheat_Sheet.html), dan [OWASP Third Party JavaScript Management Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Third_Party_Javascript_Management_Cheat_Sheet.html).

## Caveat dan batasan

- Review ini melakukan mapping berbasis code/config evidence yang tersedia; belum melakukan authenticated/unauthenticated penetration test, browser exploit confirmation, production reverse-proxy review, registry vulnerability scan, atau secret validity check.
- “Relevan” berarti kategori OWASP membantu menjelaskan akar masalah atau kontrolnya; bukan berarti vulnerability sudah terbukti exploitable atau severity sudah final.
- OWASP sendiri menyarankan [ASVS](https://owasp.org/www-project-application-security-verification-standard/) untuk verification yang komprehensif. Gunakan catatan ini sebagai triage dan backlog hardening, bukan sebagai klaim compliance.
