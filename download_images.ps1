# Create directories if they don't exist
$directories = @(
    "assets/images/hero",
    "assets/images/gallery",
    "assets/images/hotels",
    "assets/images/team",
    "assets/images/awards",
    "assets/images/icons",
    "assets/images/destinations"
)

foreach ($dir in $directories) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force
    }
}

# Download hero images
$heroImages = @{
    "main-hero.jpg" = "https://images.unsplash.com/photo-1539768942893-daf53e448371?q=80"
    "gallery-hero.jpg" = "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80"
    "explore-hero.jpg" = "https://images.unsplash.com/photo-1549633030-89d0743bad01?q=80"
    "about-hero.jpg" = "https://images.unsplash.com/photo-1600607686527-6fb886090705?q=80"
    "book-hero.jpg" = "https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80"
    "contact-hero.jpg" = "https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80"
}

# Download gallery images
$galleryImages = @{
    "room1.jpg" = "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80"
    "room2.jpg" = "https://images.unsplash.com/photo-1595576508898-0ad5c879a061?q=80"
    "pool.jpg" = "https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?q=80"
    "spa.jpg" = "https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80"
    "view1.jpg" = "https://images.unsplash.com/photo-1548588627-f978862b85e1?q=80"
    "view2.jpg" = "https://images.unsplash.com/photo-1543489822-c49534f3271f?q=80"
    "restaurant1.jpg" = "https://images.unsplash.com/photo-1592861956120-e524fc739696?q=80"
    "restaurant2.jpg" = "https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?q=80"
}

# Download hotel images
$hotelImages = @{
    "hotel1.jpg" = "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80"
    "hotel2.jpg" = "https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80"
    "hotel3.jpg" = "https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80"
    "hotel1-1.jpg" = "https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80"
    "hotel1-2.jpg" = "https://images.unsplash.com/photo-1630660664869-c9d3cc676880?q=80"
    "hotel1-3.jpg" = "https://images.unsplash.com/photo-1584132967334-10e028bd69f7?q=80"
}

# Download team images
$teamImages = @{
    "ceo.jpg" = "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80"
    "manager.jpg" = "https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80"
    "designer.jpg" = "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80"
}

# Download award images
$awardImages = @{
    "award1.jpg" = "https://images.unsplash.com/photo-1621274147744-cfb5694bb233?q=80"
    "award2.jpg" = "https://images.unsplash.com/photo-1621274147744-cfb5694bb233?q=80"
    "award3.jpg" = "https://images.unsplash.com/photo-1621274147744-cfb5694bb233?q=80"
}

# Download destination images
$destinationImages = @{
    "cairo.jpg" = "https://images.unsplash.com/photo-1572252009286-268acec5ca0a?q=80"
    "sharm.jpg" = "https://images.unsplash.com/photo-1469796466635-455ede028aca?q=80"
    "hurghada.jpg" = "https://images.unsplash.com/photo-1599640842225-85d111c60e6b?q=80"
}

# Function to download images
function Download-Image {
    param (
        [string]$url,
        [string]$outputPath
    )
    try {
        Invoke-WebRequest -Uri $url -OutFile $outputPath
        Write-Host "Downloaded: $outputPath"
    }
    catch {
        $errorMessage = $_.Exception.Message
        Write-Host "Error downloading ${outputPath}: ${errorMessage}"
    }
}

# Download all images
foreach ($image in $heroImages.GetEnumerator()) {
    Download-Image -url $image.Value -outputPath "assets/images/hero/$($image.Key)"
}

foreach ($image in $galleryImages.GetEnumerator()) {
    Download-Image -url $image.Value -outputPath "assets/images/gallery/$($image.Key)"
}

foreach ($image in $hotelImages.GetEnumerator()) {
    Download-Image -url $image.Value -outputPath "assets/images/hotels/$($image.Key)"
}

foreach ($image in $teamImages.GetEnumerator()) {
    Download-Image -url $image.Value -outputPath "assets/images/team/$($image.Key)"
}

foreach ($image in $awardImages.GetEnumerator()) {
    Download-Image -url $image.Value -outputPath "assets/images/awards/$($image.Key)"
}

foreach ($image in $destinationImages.GetEnumerator()) {
    Download-Image -url $image.Value -outputPath "assets/images/destinations/$($image.Key)"
}

# Download icons
$icons = @{
    "location.png" = "https://cdn-icons-png.flaticon.com/256/2776/2776067.png"
    "phone.png" = "https://cdn-icons-png.flaticon.com/256/126/126341.png"
    "email.png" = "https://cdn-icons-png.flaticon.com/256/3178/3178158.png"
}

foreach ($icon in $icons.GetEnumerator()) {
    Download-Image -url $icon.Value -outputPath "assets/images/icons/$($icon.Key)"
} 