<div class="modal" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form id="addForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Name:</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="name" placeholder="Enter your Name" class="form-control">
                            </div>
                        </div>
                        <div style="margin-top: 10px;"></div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label>Email:</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="email" name="email" placeholder="Enter your email" class="form-control">
                            </div>
                        </div>
                        <div style="margin-top: 10px;"></div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label>Mobile:</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="mobile" placeholder="Enter your mobile" class="form-control"
                                    pattern="[1-9][0-9]{9}" title="Enter valid 10 digit mobile number">
                            </div>
                        </div>
                        <div style="margin-top: 10px;"></div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label>Image:</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>
                        <div style="margin-top: 10px;"></div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label>Description:</label>
                            </div>
                            <div class="col-sm-9">
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div style="margin-top: 10px;"></div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>