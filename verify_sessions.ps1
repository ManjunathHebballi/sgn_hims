# Session Configuration Verification Script
$ErrorActionPreference = 'SilentlyContinue'

Write-Host "Scanning PHP files..." -ForegroundColor Cyan

$allPhpFiles = @()
$filesWithSessionStart = @()
$filesWithSavePath = @()
$filesMissingSavePath = @()

# Get all PHP files
Get-ChildItem -Path . -Recurse -Filter "*.php" -File | ForEach-Object {
    $filePath = $_.FullName
    $relativePath = $_.FullName.Replace((Get-Location).Path + '\', '')
    
    try {
        $content = Get-Content $filePath -Raw -ErrorAction Stop
        
        if ($content -match 'session_start\(\)') {
            $filesWithSessionStart += $relativePath
            
            if ($content -match "ini_set\('session\.save_path'") {
                $filesWithSavePath += $relativePath
            } else {
                $filesMissingSavePath += $relativePath
            }
        }
    } catch {
        # Skip files that can't be read
    }
}

Write-Host "`n=== FINAL VERIFICATION RESULTS ===" -ForegroundColor Cyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray
Write-Host "Files with session_start()      : $($filesWithSessionStart.Count)" -ForegroundColor Yellow
Write-Host "Files with save_path configured : $($filesWithSavePath.Count)" -ForegroundColor Green
Write-Host "Files MISSING save_path         : $($filesMissingSavePath.Count)" -ForegroundColor $(if ($filesMissingSavePath.Count -eq 0) { 'Green' } else { 'Red' })
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

if ($filesMissingSavePath.Count -gt 0) {
    Write-Host "`n❌ FILES THAT STILL NEED FIXING:" -ForegroundColor Red
    $filesMissingSavePath | ForEach-Object { Write-Host "   $_" -ForegroundColor Red }
} else {
    Write-Host "`n✅ SUCCESS! ALL FILES PROPERLY CONFIGURED!" -ForegroundColor Green
    Write-Host "All $($filesWithSessionStart.Count) files with session_start() have the session.save_path configuration." -ForegroundColor Green
}

Write-Host ""
