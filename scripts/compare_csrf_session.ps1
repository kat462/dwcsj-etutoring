$uri = 'https://dwcsj-etutoring-system.up.railway.app'
$accounts = @(
    @{ id = 'ADMIN001'; password = 'password123' },
    @{ id = 'TUTOR001'; password = 'password123' },
    @{ id = 'STUDENT001'; password = 'password123' }
)

foreach ($acc in $accounts) {
    $cookieFile = "cookies_$($acc.id).txt"
    $outFile = "login_page_$($acc.id).html"

    Write-Host "`n--- Preparing check for $($acc.id) ---"

    Write-Host "Performing GET /login to obtain cookies and page for $($acc.id)"
    curl.exe -s -c $cookieFile "$uri/login" -o $outFile

    # extract meta csrf token from HTML
    $html = Get-Content $outFile -Raw
    $metaMatch = [regex]::Match($html, '<meta name="csrf-token" content="(?<token>[^\"]+)">')
    $metaToken = $null
    if ($metaMatch.Success) { $metaToken = $metaMatch.Groups['token'].Value }

    # read cookiejar for session cookie
    $sessLine = Select-String -Path $cookieFile -Pattern 'dwcsj_peer_e_tutoring_system_session' -SimpleMatch | Select-Object -First 1

    if (-not $sessLine) {
        Write-Host "No session cookie found for $($acc.id)"
        continue
    }

    # cookie file fields: domain, flag, path, secure, expiration, name, value
    $parts = ($sessLine -split "\s+") | Where-Object { $_ -ne '' }
    $rawSessionCookie = $parts[-1].Trim()

    # Query the server for the most-recent session token (runs in Railway env)
    # We call the helper with no arg so it returns the latest session row created by this GET.
    Write-Host "Querying server for the latest session (runs in Railway env)..."
    $cmd = "railway run php scripts/find_latest_session_token.php"
    Write-Host "Running: $cmd"
    $output = Invoke-Expression $cmd 2>&1

    Write-Host "--- Server session lookup output ---"
    Write-Host $output

    # extract session token from output
    $tokenMatch = Select-String -InputObject $output -Pattern '_token:\s*(?<tok>\S+)' -AllMatches
    $sessionToken = $null
    if ($tokenMatch) { $sessionToken = ($tokenMatch.Matches[0].Groups['tok'].Value) }

    Write-Host "Meta token:    $metaToken"
    Write-Host "Session token: $sessionToken"

    if ($metaToken -and $sessionToken -and ($metaToken -eq $sessionToken)) {
        Write-Host "Result: TOKENS MATCH for $($acc.id)"
    } else {
        Write-Host "Result: TOKENS DO NOT MATCH for $($acc.id)"
    }

    # Now attempt a POST login using the cookiejar and extracted meta token
    if ($metaToken) {
        $escaped = [System.Uri]::EscapeDataString($metaToken)
        $postData = "_token=$escaped&student_id=$($acc.id)&password=$($acc.password)"
        Write-Host "Attempting POST /login for $($acc.id) using cookiejar $cookieFile..."
        $resp = curl.exe -i -s -b $cookieFile -c $cookieFile -H "Content-Type: application/x-www-form-urlencoded" -d $postData "$uri/login"
        $statusLine = ($resp -split "\r?\n")[0]
        Write-Host "POST response status: $statusLine"
        Write-Host "POST response preview (last 10 lines):"
        $lines = $resp -split "\r?\n"
        if ($lines.Length -gt 10) { $last = $lines[-10..-1] -join "`n" } else { $last = $lines -join "`n" }
        Write-Host $last
    } else {
        Write-Host "Skipping POST because no meta token found"
    }
}
