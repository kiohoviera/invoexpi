@extends('welcome')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">
                        Upload Inventory
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="/import" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label>CSV</label>
                            <input type="file" name="file_upload" class="form-control" required />
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-success btn-block w-100"><i class="bx bxs-cloud-upload"></i> Upload File </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
