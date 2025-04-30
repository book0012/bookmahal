<form action="place_order.php" method="POST">
    <input type="hidden" name="book_id" value="BOOK_ID_HERE">
    <label>Quantity:</label>
    <input type="number" name="quantity" min="1" value="1" required>
    
    <label>Shipping Address:</label>
    <textarea name="shipping_address" required></textarea>

    <label>Payment Method:</label>
    <select name="payment_method">
        <option value="COD">Cash on Delivery</option>
        <option value="Online">Online Payment</option>
    </select>

    <button type="submit">📦 Place Order</button>
</form>
