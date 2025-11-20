Param(
    [string]$CookieFile = 'cookies_ADMIN001.txt'
)
if (-not (Test-Path $CookieFile)) {
    Write-Error "Cookie file $CookieFile not found"
    exit 1
}
$lines = Get-Content $CookieFile
$xline = $lines | Where-Object { $_ -match 'XSRF-TOKEN' } | Select-Object -First 1
$sline = $lines | Where-Object { $_ -match 'dwcsj_peer_e_tutoring_system_session' } | Select-Object -First 1
if ($xline) {
    $fields = -split $xline
    $rawX = $fields[-1].Trim()
    $unescaped = [System.Uri]::UnescapeDataString($rawX)
    Write-Output "XSRF_RAW=$rawX"
    Write-Output "XSRF=$unescaped"
} else { Write-Output "XSRF not found" }
if ($sline) {
    $fields = -split $sline
    $rawS = $fields[-1].Trim()
    Write-Output "SESSION=$rawS"
} else { Write-Output "SESSION not found" }
