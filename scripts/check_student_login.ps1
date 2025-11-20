$uri = 'https://dwcsj-etutoring-system.up.railway.app'
$cookieFile = 'cookies_STUDENT001.txt'
$loginHtml = 'login_page_STUDENT001.html'

Write-Host "GET /login"
curl.exe -s -c $cookieFile "$uri/login" -o $loginHtml

$html = Get-Content $loginHtml -Raw
$m = [regex]::Match($html, '<meta name="csrf-token" content="(?<tok>[^"]+)">')
if (-not $m.Success) { Write-Host "CSRF meta tag not found"; exit 1 }
$token = $m.Groups['tok'].Value
Write-Host "Found CSRF token: $token"

$postData = "_token=$([System.Uri]::EscapeDataString($token))&student_id=STUDENT001&password=password123"

Write-Host "POST /login"
$resp = curl.exe -i -s -b $cookieFile -c $cookieFile -H "Content-Type: application/x-www-form-urlencoded" -d $postData "$uri/login"
Write-Host "----- RESPONSE START -----"
Write-Host $resp
Write-Host "----- RESPONSE END -----"

# Try to extract Railway request id if present in JSON body
if ($resp -match '"request_id"\s*:\s*"(?<id>[^"]+)"') {
    Write-Host "Found request_id: $($matches['id'])"
}
