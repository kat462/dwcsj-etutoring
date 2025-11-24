param(
    [int]$iterations = 50,
    [string]$accountId = 'ADMIN001',
    [string]$password = 'password123'
)

$uri = 'https://dwcsj-etutoring-system.up.railway.app'
$failures = @()

for ($i = 1; $i -le $iterations; $i++) {
    $cookieFile = "stress_${accountId}_cookies.txt"
    $pageFile = "stress_${accountId}_login_$i.html"

    Write-Host ("Iteration {0}: GET /login" -f $i)
    curl.exe -s -c $cookieFile "$uri/login" -o $pageFile

    Start-Sleep -Milliseconds 200

    # extract token
    $html = Get-Content $pageFile -Raw
    $m = [regex]::Match($html, '<meta name="csrf-token" content="(?<tok>[^\"]+)">')
    if (-not $m.Success) {
        Write-Host "  ERROR: no meta token found on GET"
        $failures += "iter:$i no-meta"
        continue
    }
    $token = $m.Groups['tok'].Value

    $escaped = [System.Uri]::EscapeDataString($token)
    $postData = "_token=$escaped&student_id=$accountId&password=$password"

    Write-Host "  POST /login (using cookiejar)"
    $resp = curl.exe -i -s -b $cookieFile -c $cookieFile -H "Content-Type: application/x-www-form-urlencoded" -d $postData "$uri/login"
    $statusLine = ($resp -split "\r?\n")[0]

    if ($statusLine -notmatch '302|200') {
        Write-Host "  FAILURE at iter $i -> $statusLine"
        $failures += "iter:$i -> $statusLine"
    } else {
        Write-Host "  OK -> $statusLine"
    }

    Start-Sleep -Milliseconds 150
}

Write-Host "\nStress test complete. Failures: $($failures.Count)"
if ($failures.Count -gt 0) { $failures | ForEach-Object { Write-Host $_ } }
else { Write-Host 'No failures observed.' }
