$uri = 'https://dwcsj-etutoring-system.up.railway.app'
$accounts = @(
    @{ id = 'ADMIN001'; password = 'password123' },
    @{ id = 'TUTOR001'; password = 'password123' },
    @{ id = 'STUDENT001'; password = 'password123' }
)

$endpoints = @(
    @{ name = 'Dashboard'; path = '/%ROLE%/dashboard' },
    @{ name = 'Bookings List'; path = '/%ROLE%/bookings' },
    @{ name = 'Calendar'; path = '/%ROLE%/calendar' },
    @{ name = 'Profile'; path = '/tutor/profile' },
    @{ name = 'Request Session'; path = '/student/bookings' },
    @{ name = 'Feedback'; path = '/student/feedback' },
    @{ name = 'Admin Users'; path = '/admin/users' },
    @{ name = 'Allowed Student IDs'; path = '/admin/allowed-student-ids' }
)

function Do-Login {
    param($acc, $cookieFile, $outFile)

    Write-Host "  GET /login"
    curl.exe -s -c $cookieFile "$uri/login" -o $outFile

    $html = Get-Content $outFile -Raw
    $metaMatch = [regex]::Match($html, '<meta name="csrf-token" content="(?<token>[^"]+)"')
    if (-not $metaMatch.Success) { Write-Host "    No CSRF meta token found"; return $false }
    $token = $metaMatch.Groups['token'].Value

    $postData = "_token=$([System.Uri]::EscapeDataString($token))&student_id=$($acc.id)&password=$($acc.password)"
    Write-Host "  POST /login (using cookiejar)"
    $resp = curl.exe -i -s -b $cookieFile -c $cookieFile -H "Content-Type: application/x-www-form-urlencoded" -d $postData "$uri/login"
    $firstLine = ($resp -split "\r?\n")[0]
    if ($firstLine -match 'HTTP/\S+\s+(\d{3})') { $code = $matches[1] } else { $code = 'N/A' }
    Write-Host "    -> $firstLine"
    return $code -eq '302' -or $code -eq '200'
}

foreach ($acc in $accounts) {
    Write-Host "`n--- Smoke pages for $($acc.id) ---"
    $cookieFile = "cookies_smoke_$($acc.id).txt"
    $outFile = "login_smoke_$($acc.id).html"

    $ok = Do-Login -acc $acc -cookieFile $cookieFile -outFile $outFile
    if (-not $ok) { Write-Host "Login failed for $($acc.id), skipping page checks"; continue }

    # Determine role-based prefix used in routes
    $rolePrefix = switch ($acc.id) {
        'ADMIN001' { 'admin' }
        'TUTOR001' { 'tutor' }
        default { 'student' }
    }

    foreach ($ep in $endpoints) {
        $path = $ep.path -replace '%ROLE%', $rolePrefix
        $url = $uri + $path
        Write-Host "  GET $path"
        $resp = curl.exe -i -s -b $cookieFile -c $cookieFile $url
        $lines = $resp -split "\r?\n"
        $statusLine = $lines | Where-Object { $_ -match '^HTTP/' } | Select-Object -First 1
        if ($statusLine -match 'HTTP/\S+\s+(\d{3})') { $code = $matches[1] } else { $code = 'N/A' }
        Write-Host "    $($ep.name): $code -- $statusLine"
        if ($code -ge 400) {
            $body = ($lines | Select-Object -Last 400) -join "`n"
            Write-Host "    Body preview (last 400 lines):`n$body"
        }
    }
}
