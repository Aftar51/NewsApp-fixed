@extends('home.parent')

@section('content')
    <div class="row">
        <div class="card p-4">
            <h3>News Create</h3>

            <form action="{{ route('news.update', $news->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- // field untuk title --}}
                {{-- // name berfungsi untuk --}}
                {{-- // mengirimkan data ke controller --}}
                {{-- // fungsi old berfungsi untuk --}}
                {{-- // menampilkan kembali inputan user --}}
                <div class="mb-2">
                    <label for="inputTitle" class="form-label">News Title</label>
                    <input type="text" class="form-control" id="inputTitle" name="title" value="{{ $news->title }}">
                </div>

                {{-- // field untuk image --}}
                {{-- // name berfungsi untuk --}}
                {{-- // mengirimkan data ke controller --}}
                {{-- // fungsi old berfungsi untuk --}}
                {{-- // menampilkan kembali inputan user --}}
                <div class="mb-2">
                    <label for="inputImage" class="form-label">News Image</label>
                    <input type="file" class="form-control" id="inputImage" name="image" value="{{ old('image') }}">
                </div>

                <div class="mb-2">
                    <label class="col col-form-label">Category News</label>
                    <div class="col">
                        <select name="category_id" class="form-select" aria-label="Default select example">
                            <option selected value="{{ $news->category->id }}">{{ $news->category->name }}</option>
                            <option >=== Choose Category ===</option>
                            @foreach ($category as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                {{-- // field untuk content --}}
                {{-- // menggunakan ckeditor 
                        untuk menampilkan content --}}
                {{-- // name berfungsi untuk 
                        mengirimkan data ke controller --}}
                <div class="mb-2">
                    <label class="col col-form-label">Content News</label>
                    <textarea id="editor" name="content">
                        {!! $news->content !!}
                    </textarea>

                    <script>
                        ClassicEditor
                            .create(document.querySelector('#editor'))
                            .then(editor => {
                                console.log(editor);
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    </script>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-warning" type="submit">
                        <i class="bi bi-pencil"></i>
                        Update News
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
