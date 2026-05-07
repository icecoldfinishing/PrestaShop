<template>
    <form>
    <div class="mb-3">
        <label for="productName" class="form-label">Product name</label>
        <input type="text" class="form-control" id="productName" aria-describedby="emailHelp">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control" id="description">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</template>