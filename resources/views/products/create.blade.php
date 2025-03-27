// ... existing code ...
<div class="form-group">
    <label for="keywords">Keywords for Product Name</label>
    <input type="text" class="form-control" id="keywords" placeholder="Enter keywords">
    <button type="button" class="btn btn-secondary mt-2" onclick="suggestName()">Generate AI Name</button>
</div>

<div class="form-group">
    <label for="name">Product Name</label>
    <input type="text" class="form-control" id="name" name="name">
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" id="description" name="description"></textarea>
    <button type="button" class="btn btn-secondary mt-2" onclick="generateDescription()">Generate AI Description</button>
</div>

<div class="form-group">
    <label for="category">Category</label>
    <input type="text" class="form-control" id="category" name="category">
    <button type="button" class="btn btn-secondary mt-2" onclick="recommendCategory()">Recommend AI Category</button>
</div>

<div class="form-group">
    <label for="price">Price (IDR)</label>
    <input type="number" class="form-control" id="price" name="price">
    <button type="button" class="btn btn-secondary mt-2" onclick="suggestPrice()">Suggest AI Price</button>
</div>

@push('scripts')
<script>
function generateDescription() {
    const productName = document.getElementById('name').value;
    
    fetch('/ai/generate-description', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name: productName })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('description').value = data.description;
    });
}

function suggestName() {
    const keywords = document.getElementById('keywords').value;
    
    fetch('/ai/suggest-name', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ keywords: keywords })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('name').value = data.name;
    });
}

function recommendCategory() {
    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    
    fetch('/ai/recommend-category', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            name: name,
            description: description
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('category').value = data.category;
    });
}

function suggestPrice() {
    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    
    fetch('/ai/suggest-price', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            name: name,
            description: description
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('price').value = data.price.replace(/[^0-9]/g, '');
    });
}
</script>
@endpush