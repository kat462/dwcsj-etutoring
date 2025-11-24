$uri = 'https://dwcsj-etutoring-system.up.railway.app'
$accounts = @(
    @{ id = 'ADMIN001'; password = 'password123' },
    @{ id = 'TUTOR001'; password = 'password123' },
    @{ id = 'STUDENT001'; password = 'password123' }
)

foreach ($acc in $accounts) {
    $cookieFile = "cookies_$($acc.id).txt"
    $outFile = "login_page_$($acc.id).html"

    Write-Host "\n--- Checking login for $($acc.id) ---"

    # GET /login to receive cookies and CSRF token
    curl.exe -s -c $cookieFile "$uri/login" -o $outFile

    # extract CSRF meta token from the login page (use the meta tag, not the encrypted cookie)
    $html = Get-Content $outFile -Raw
    $metaMatch = [regex]::Match($html, '<meta name="csrf-token" content="(?<token>[^"]+)"')
    if (-not $metaMatch.Success) {
        Write-Host "No CSRF meta token found for $($acc.id)"
        continue
    }
    $token = $metaMatch.Groups['token'].Value

    # POST /login with form data, include cookiejar
    $postData = "_token=$([System.Uri]::EscapeDataString($token))&student_id=$($acc.id)&password=$($acc.password)"
    $response = curl.exe -i -s -b $cookieFile -c $cookieFile -H "Content-Type: application/x-www-form-urlencoded" -d $postData "$uri/login"

    # Print relevant response headers and a small body preview
    $responseLines = $response -split "\r?\n"
    $head = $responseLines | Where-Object { $_ -match 'HTTP/|Location:|Set-Cookie:|XSRF-TOKEN|dwcsj_peer_e_tutoring_system_session|Status:' } 
    Write-Host ($head -join "`n")
    $bodyPreview = ($responseLines | Select-Object -Last 20) -join "`n"
    Write-Host "\n--- Body preview (last 20 lines) ---\n$bodyPreview"
}
