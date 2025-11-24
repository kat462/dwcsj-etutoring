param(
    [string]$id = 'ADMIN001',
    [string]$password = 'password123'
)
$uri = 'https://dwcsj-etutoring-system.up.railway.app'
$cookieFile = "debug_cookies_$id.txt"
$outFile = "debug_login_$id.html"

Write-Host "GET /login"
curl.exe -v -s -c $cookieFile "$uri/login" -o $outFile 2>&1 | Write-Host

Write-Host "\n--- Cookie file contents ---"
Get-Content $cookieFile | Write-Host

$html = Get-Content $outFile -Raw
$m = [regex]::Match($html, '<meta name="csrf-token" content="(?<tok>[^"]+)">')
if ($m.Success) { $metaToken = $m.Groups['tok'].Value; Write-Host "Meta token: $metaToken" } else { Write-Host "Meta token not found" }

# extract XSRF cookie
$xsrfLine = Select-String -Path $cookieFile -Pattern 'XSRF-TOKEN' -SimpleMatch | Select-Object -First 1
if ($xsrfLine) {
    Write-Host "XSRF cookie line: $xsrfLine"
    $parts = $xsrfLine -split "\t"
    $rawToken = $parts[-1].Trim()
    $token = [System.Uri]::UnescapeDataString($rawToken)
    Write-Host "RawToken: $rawToken"; Write-Host "Decoded token: $token"
} else { Write-Host "No XSRF cookie found" }

# session cookie
$sessLine = Select-String -Path $cookieFile -Pattern 'dwcsj_peer_e_tutoring_system_session' -SimpleMatch | Select-Object -First 1
if ($sessLine) { Write-Host "Session cookie line: $sessLine" } else { Write-Host "No session cookie found" }

$postData = "_token=$([System.Uri]::EscapeDataString($token))&student_id=$id&password=$password"
Write-Host "POSTing: $postData"
$response = curl.exe -v -i -s -b $cookieFile -c $cookieFile -H "Content-Type: application/x-www-form-urlencoded" -d $postData "$uri/login" 2>&1
Write-Host "\n--- Response ---\n"
Write-Host $response
