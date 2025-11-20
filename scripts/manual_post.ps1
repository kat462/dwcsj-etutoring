$uri = 'https://dwcsj-etutoring-system.up.railway.app'
$cookieFile = 'cookies_ADMIN001.txt'
if (-not (Test-Path $cookieFile)) { Write-Host "Cookie file $cookieFile not found"; exit 1 }

# cookie file format: domain \t flag \t path \t secure \t expiration \t name \t value
$lines = Get-Content $cookieFile | Where-Object { $_ -and -not ($_ -match '^#') }
$cookies = @{ }
foreach ($l in $lines) {
    $cols = $l -split "\t"
    if ($cols.Length -ge 7) {
        $name = $cols[6].Trim()
        $value = $cols[7].Trim()
        $cookies[$name] = $value
    }
}

if (-not $cookies.ContainsKey('XSRF-TOKEN') -or -not $cookies.ContainsKey('dwcsj_peer_e_tutoring_system_session')) {
    Write-Host "Required cookies not present in $cookieFile"; exit 1
}

$xsrf = [System.Uri]::UnescapeDataString($cookies['XSRF-TOKEN'])
$session = $cookies['dwcsj_peer_e_tutoring_system_session']

Write-Host "Using session cookie length: $($session.Length) and XSRF token length: $($xsrf.Length)"

$postData = "_token=$([System.Uri]::EscapeDataString($xsrf))&student_id=ADMIN001&password=password123"

$cookieHeader = "XSRF-TOKEN=$([System.Uri]::EscapeDataString($xsrf)); dwcsj_peer_e_tutoring_system_session=$session"

Write-Host "POSTing to $uri/login with explicit Cookie header..."

$resp = curl.exe -i -s -H "Content-Type: application/x-www-form-urlencoded" -H "Cookie: $cookieHeader" -d $postData "$uri/login"
Write-Host $resp
