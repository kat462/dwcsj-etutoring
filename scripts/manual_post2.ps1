$uri = 'https://dwcsj-etutoring-system.up.railway.app'
$cookieFile = 'cookies_ADMIN001.txt'
$loginHtml = 'login_page_ADMIN001.html'
if (-not (Test-Path $cookieFile)) { Write-Host "Cookie file $cookieFile not found"; exit 1 }
if (-not (Test-Path $loginHtml)) { Write-Host "Login HTML $loginHtml not found"; exit 1 }

$html = Get-Content $loginHtml -Raw
$m = [regex]::Match($html, '<meta name="csrf-token" content="(?<tok>[^"]+)">')
if (-not $m.Success) { Write-Host "CSRF meta tag not found in $loginHtml"; exit 1 }
$token = $m.Groups['tok'].Value
Write-Host "Found raw CSRF token: " + $token

$postData = "_token=$([System.Uri]::EscapeDataString($token))&student_id=ADMIN001&password=password123"

Write-Host "POSTing with cookiejar $cookieFile and raw token..."
$resp = curl.exe -i -s -b $cookieFile -c $cookieFile -H "Content-Type: application/x-www-form-urlencoded" -d $postData "$uri/login"
Write-Host $resp
