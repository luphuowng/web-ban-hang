<?php require_once 'partials/head.php' ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">QUẢN LÝ NHÓM HÀNG</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow-sm" id="addCategoryBtn">
            <i class="fas fa-plus-circle fa-sm text-white-50"></i>
            Thêm Nhóm hàng
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách nhóm hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered categoryTable" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Tên nhóm hàng</th>
                        <th>Thời gian tạo</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var ArrayObject $categories */
                    foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category->name ?></td>
                            <td><?= $category->created_at ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm edit" data-id="<?= $category->id ?>">Chỉnh
                                    sửa
                                </button>
                                <button class="btn btn-danger btn-sm delete" data-id="<?= $category->id ?>">
                                    Xóa
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Logout Modal-->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Thêm mới nhóm hàng</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-2">
                    <div id="alert-message" class="alert alert-danger alert-dismissible fade show" role="alert"
                         style="display: none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form>
                        <input type="hidden" name="categoryId" id="categoryId">
                        <div class="form-group">
                            <label for="name">Tên nhóm hàng</label>
                            <input type="text" class="form-control" id="name" placeholder="Nhập tên nhóm hàng">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Hủy</button>
                <button class="btn btn-primary" id="categorySubmit" type="button">Thêm</button>
            </div>
        </div>
    </div>
</div>

<?php require_once 'partials/scripts.php' ?>

<script>
    function renderModalTitle() {
        const id = $("#categoryId").val();
        if (id) {
            $("#categoryModalTitle").text('Chỉnh sửa nhóm hàng');
            $('#categorySubmit').text('Cập nhật');
        } else {
            $("#categoryModalTitle").text('Thêm mới nhóm hàng');
            $('#categorySubmit').text('Thêm mới');
        }
    }

    function prependCategory(category) {
        const string = `
            <tr>
                <td>${category.name}</td>
                <td>${category.created_at}</td>
                <td>
                    <button class="btn btn-primary btn-sm edit" data-id="${category.id}">Chỉnh
                        sửa
                    </button>
                    <button class="btn btn-danger btn-sm delete" data-id="${category.id}">
                        Xóa
                    </button>
                </td>
            </tr>
        `;

        $(".categoryTable tbody").prepend(string);
    }

    function clearForm() {
        document.querySelector("#name").value = '';
    }

    $(document).ready(function () {
        let editImpactor;

        $('#addCategoryBtn').click(function () {
            renderModalTitle();
            $('#categoryModal').modal('show');
        })

        $('#categorySubmit').click(function (event) {
            const id = $("#categoryId").val();
            const name = $("#name").val();

            const url = id ? '/categories/update' : '/categories';
            const successMessage = !id ? 'Thêm mới nhóm hàng thành công!' : 'Cập nhật nhóm hàng thành công';

            if (!name) {
                console.log(name)
                renderMessage('Không được để trống tên của nhóm hàng', 'error');
                return;
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: {id, name},
                success: function (response) {
                    const {category} = JSON.parse(response);
                    console.log(response)
                    if (response.error) {
                        renderMessage(response.error, 'error');
                    } else {
                        if (id) {
                            table.row($(editImpactor).parents('tr')).remove().draw();
                        } else {
                            clearForm();
                        }
                        prependCategory(category);
                        renderMessage(successMessage);
                    }
                },
                error: function (response) {
                    renderMessage('Server Error', 'error')
                }
            });
        });

        $('.edit').click(function () {
            const impactor = this;
            editImpactor = this;
            const id = $(impactor).data("id");
            document.querySelector('#categoryId').value = id;
            renderModalTitle();
            $('#categoryModal').modal('show');

            $.ajax({
                method: 'GET',
                url: '/categories/one',
                data: {id},
                success: function (response) {
                    const {category} = JSON.parse(response);
                    document.querySelector("#name").value = category.name;
                },
                error: function (error) {
                    console.log(error)
                }
            })

        });

        $('.delete').click(function (event) {
            const impactor = this;
            const id = $(impactor).data("id");
            $.confirm({
                title: false,
                content: 'Bạn có muốn xóa nhóm hàng này không?',
                buttons: {
                    cancel: {
                        text: 'Hủy bỏ',
                        btnClass: 'btn-default'
                    },
                    delete: {
                        text: 'Đồng ý',
                        btnClass: 'btn-danger',
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: '/categories/delete',
                                data: {id},
                                success: function (response) {
                                    $.alert('Xóa nhóm hàng thành công');
                                },
                                error: function (error) {
                                    $.alert(`Server error`);
                                }
                            });
                            table.row($(impactor).parents('tr')).remove().draw();
                        }
                    }
                }
            });
        });
    });
</script>

<?php require_once 'partials/footer.php' ?>

