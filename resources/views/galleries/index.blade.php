<!DOCTYPE html>
<html>
<head>
    <title>Image Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        .gallery-item {
            position: relative;
            width: 100%;
            height: auto; 
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            margin-bottom: 20px;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .gallery-item-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .gallery-item-info h3, .gallery-item-info p {
            margin: 0;
            font-size: 14px;
        }
        .gallery-item-info a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }
        .gallery-item-info a:hover {
            text-decoration: underline;
        }
        .gallery-item-tags {
            font-size: 12px;
            color: #fff;
            margin-top: 5px;
        }
        .add-image {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .add-image:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Image Gallery</h1>
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('galleries.create') }}" class="add-image">Upload New Images</a>
        <div class="gallery-container">
            @foreach ($galleries as $gallery)
                <div class="gallery-item">
                    <img src="{{ asset('storage/' . $gallery->image_url) }}" alt="{{ $gallery->title }}">
                    <div class="gallery-item-info">
                        <div>
                            <h3>{{ $gallery->title }}</h3>
                            <div class="gallery-item-tags">
                                Tags: {{ $gallery->tags }}
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('galleries.edit', $gallery->id) }}" class="edit"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:white; cursor:pointer;">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this image?');
    }
</script>

</html>
