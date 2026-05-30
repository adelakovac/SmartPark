<form method="POST" action="/locations">
    @csrf

    <input type="text" name="name" placeholder="Name"><br>
    <input type="text" name="address" placeholder="Address"><br>
    <input type="text" name="city" placeholder="City"><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input type="number" name="total_spots" placeholder="Total spots"><br>

    <button type="submit">Save</button>
</form>