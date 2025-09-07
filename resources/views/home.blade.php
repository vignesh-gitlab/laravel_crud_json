<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="container">
        <h1 style="text-align: center;">CRUD using JSON</h1>
        <button class="btn btn-primary" id="add">ADD</button>
        <table class="table table-bordered table-striped" style="margin-top: 10px;">
            <thead>
                <th>S.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Image</th>
                <th>Description</th>
                <th>Action</th>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
        @include('modal')
    </div>

    <script>
    $(document).ready(function() {
        loadUserTable();
    });

    $('#add').click(function() {
        $('#addModal').modal('show');
        $('#addForm')[0].reset();
    });

    function loadUserTable() {
        $.ajax({
            url: '/getUsers',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#tbody').html(response.html);
            }
        });
    }

    $('#addForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: '/createUser',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert(response.message);
                    $('#addModal').modal('hide');
                    loadUserTable();
                } else {
                    alert(response.message);
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    });

    $(document).on('click', '.delete', function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/getUserDetail/' + id,
            type: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    $('#delModal').modal('show');
                    $('#delUser').text(response.user.name);
                    $('#delid').val(response.user.id);
                } else {
                    alert("Error in get user");
                }
            }
        });
    });

    $('#delForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#delid').val();
        var formData = new FormData(this);

        $.ajax({
            url: '/deleteUser/' + id,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert(response.message);
                    $('#delModal').modal('hide');
                    loadUserTable();
                } else {
                    alert(response.message);
                }
            }
        });
    });


    $(document).on('click', '.edit', function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/getUserDetail/' + id,
            type: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    var user = response.user;
                    $('#editid').val(user.id);
                    $('#name').val(user.name);
                    $('#email').val(user.email);
                    $('#mobile').val(user.mobile);
                    $('#editForm input[type="file"]').val('');
                    if (user.image == '') {
                        $('#imagePreview').attr('src', '');
                    } else {
                        var basePath = '{{asset("storage")}}/';
                        $('#imagePreview').attr('src', basePath + user.image);
                    }
                    $('#description').val(user.description);
                    $('#editModal').modal('show');
                }
            }
        });
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editid').val();
        var formData = new FormData(this);

        $.ajax({
            url: '/editUser/' + id,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert(response.message);
                    $('#editModal').modal('hide');
                    loadUserTable();
                } else {
                    alert(response.message);
                }
            }
        });
    });
    </script>
</body>

</html>