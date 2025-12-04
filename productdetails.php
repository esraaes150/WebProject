<?php
$conn = new mysqli('localhost', 'root', '', 'furniture_store');

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// نفترض إن id المنتج جاي من الرابط
$id = intval($_GET['id']); 

$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if($result->num_rows > 0){
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $product['name']; ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="./css/bootstrap.css">
<link rel="stylesheet" href="./css/productdetails.css"> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="left col-12 col-lg-6">
            <img src="images/<?php echo $product['image']; ?>" id="mainImg" class="main-img" alt="<?php echo $product['name']; ?>">
        </div>

        <div class="right col-12 col-lg-6">
            <h1><?php echo $product['name'];?></h1>
            <div class="price"><?php echo $product['price'];?>$</div>
            
            <div class="stars">
                <i class="fa-solid fa-star" style="color: #FFD43B;"></i>
                <i class="fa-solid fa-star" style="color: #FFD43B;"></i>
                <i class="fa-solid fa-star" style="color: #FFD43B;"></i>
                <i class="fa-solid fa-star" style="color: #FFD43B;"></i>
                <i class="fa-regular fa-star"></i>
                <span class="review-count">(1 CUSTOMER REVIEW)</span>
            </div>
            
            <p><?php echo $product['description'];?></p>
            
            <div class="quantity-cart">
                <input type="number" id="productQty" value="1" min="1" class="form-control w-25 d-inline-block">
                <button id="addToCartBtn" class="btn btn-dark">Add to Cart</button>
            </div>
            
            <div class="wishlist mt-3">
                <i class="fa-solid fa-heart" style="color: #000000;"></i>
                <span>ADD TO WISHLIST</span>
            </div>
            
            <div class="details mt-3">
                <div>SKU: O23</div>
                <div>Categories: <a href="#">Accessories</a>, <a href="#">Fancies</a></div>
                <div>Tags: <a href="#">Decoration</a>, <a href="#">Modern</a></div>
            </div>
        </div>
    </div>
</div>

<script>
function changeImg(el) {
    document.getElementById('mainImg').src = el.src;
}

// إضافة المنتج للكار بدون إعادة تحميل الصفحة
document.getElementById('addToCartBtn').addEventListener('click', function() {
    const qty = document.getElementById('productQty').value;
    const productId = <?= $product['id'] ?>;

    fetch(`add_to_cart.php?id=${productId}&qty=${qty}&ajax=1`)
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success'){
            alert("Product added to cart!");
            // تحديث أيقونة الكارت في الهيدر إذا موجودة
            const cartTotalEl = document.getElementById('cart-total');
            if(cartTotalEl){
                const current = parseInt(cartTotalEl.innerText.replace('$','')) || 0;
                cartTotalEl.innerText = '$' + (current + (<?= $product['price'] ?> * qty));
            }
        } else {
            alert("Error adding product to cart");
        }
    });
});
</script>

</body>
</html>
