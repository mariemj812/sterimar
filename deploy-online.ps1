# Deploy the Sterimar static site to OVH FTP
# -------------------------------------------------
$ftpHost = "ftp.cluster129.hosting.ovh.net"
$ftpUser = "sterimq"
$ftpPass = "FTP123sterimar"
$localRoot = "H:\stérimar"

function Ensure-RemoteDir([string]$remotePath) {
    if ($remotePath -eq "" -or $remotePath -eq "/") { return }
    $uri = "ftp://$ftpHost$remotePath"
    $req = [System.Net.FtpWebRequest]::Create($uri)
    $req.Method = [System.Net.WebRequestMethods+Ftp]::MakeDirectory
    $req.Credentials = New-Object System.Net.NetworkCredential($ftpUser, $ftpPass)
    try {
        $req.GetResponse() | Out-Null
    } catch {
        # Ignore error if directory already exists (550) or other benign errors
        if ($_.Exception -and $_.Exception.Response) {
            $status = $_.Exception.Response.StatusDescription
            if ($status -notmatch "550") { Write-Host "[WARN] Could not create ${remotePath}: $status" }
        }
    }
}

Write-Host "Starting upload..."

Get-ChildItem -Path $localRoot -Recurse -File | Where-Object { $_.FullName -notmatch '\\.git\\' } | ForEach-Object {
    $relative = $_.FullName.Substring($localRoot.Length).TrimStart('\')
    $remoteDir = "/" + [System.IO.Path]::GetDirectoryName($relative).Replace('\','/')
    Ensure-RemoteDir $remoteDir

    $uri = "ftp://$ftpHost/$relative".Replace('\\','/')
    $req = [System.Net.FtpWebRequest]::Create($uri)
    $req.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
    $req.Credentials = New-Object System.Net.NetworkCredential($ftpUser, $ftpPass)
    $bytes = [System.IO.File]::ReadAllBytes($_.FullName)
    $req.ContentLength = $bytes.Length
    $stream = $req.GetRequestStream()
    $stream.Write($bytes, 0, $bytes.Length)
    $stream.Close()
    $req.GetResponse() | Out-Null
    Write-Host "Uploaded $relative"
}

Write-Host "Upload completed."
