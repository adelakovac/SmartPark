<h2>Add Parking Spot for {{ $location->name }}</h2>

<form method="POST" action="/locations/{{ $location->id }}/spots">
    @csrf

    <input type="text" name="spot_number" placeholder="Spot number"><br>

    <input type="text" name="status" placeholder="Status"><br>

    <input type="text" name="type" placeholder="Type"><br>

    <button type="submit">Save</button>
</form>