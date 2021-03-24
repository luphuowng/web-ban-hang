<?php require_once 'partials/head.php' ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">QUẢN LÝ MẶT HÀNG</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow-sm" id="addNewBtn">
            <i class="fas fa-plus-circle fa-sm text-white-50"></i>
            Thêm mặt hàng
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách mặt hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered productTable" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên mặt hàng</th>
                        <th>Loại mặt hàng</th>
                        <th>Giá</th>
                        <th>số lượng trong kho</th>
                        <th>hình ảnh</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var ArrayObject $products */
                    foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product->id ?></td>
                            <td><?= $product->name ?></td>
                            <td><?= $product->category_name ?></td>
                            <td><?= $product->price ?></td>
                            <td><?= $product->stock ?></td>
                            <td><img src="/<?= $product->image ?>" alt="<?= $product->name ?>" style="width:100px;max-height: 80px"></td>
                            <td>
                                <button class="btn btn-primary btn-sm edit" data-id="<?= $product->id ?>">Chỉnh
                                    sửa
                                </button>
                                <button class="btn btn-danger btn-sm delete" data-id="<?= $product->id ?>">
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
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="productForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Thêm mới mặt hàng</h5>
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
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name">Tên mặt hàng</label>
                            <input type="text" class="form-control" name="name" id="name"
                                   placeholder="Nhập tên mặt hàng">
                        </div>

                        <div class="form-group">
                            <label for="category_id">Giá</label>
                            <select class="form-control" name="category_id" id="category_id">
                                <option value="">Chọn loại mặt hàng...</option>
                                <?php /** @var ArrayObject $categories */
                                foreach ($categories as $category) : ?>
                                    <option value="<?= $category->id ?>"><?= $category->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price">Giá</label>
                            <input type="number" class="form-control" name="price" id="price"
                                   placeholder="Nhập giá mặt hàng">
                        </div>

                        <div class="form-group">
                            <label for="stock">Số lượng</label>
                            <input type="number" class="form-control" name="stock" id="stock"
                                   placeholder="Nhập số lượng">
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea type="number" class="form-control" name="description" id="description"
                                      placeholder="Nhập mô tả"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Hình ảnh</label>
                            <input type="file" class="form-control-file" name="image" id="image"
                                   placeholder="Chọn hình ảnh">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                    <button class="btn btn-primary" id="submit" type="submit">Thêm</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once 'partials/scripts.php' ?>

<script>
    function renderMessage(message, type = 'success') {
        $(".alert").alert();
        const alertMessage = $('#alert-message');

        alertMessage.removeClass('alert-success');
        alertMessage.removeClass('alert-danger');
        if (type === 'error') {
            alertMessage.addClass('alert-danger');
            alertMessage.css('display', 'block')
            alertMessage.text(message);
        } else {
            alertMessage.addClass('alert-success');
            alertMessage.css('display', 'block');
            alertMessage.text(message);
        }

        setTimeout(closeMessage, 3000);
    }

    function closeMessage() {
        $(".alert").css('display', 'none');
    }

    function renderModalTitle() {
        const id = $("#id").val();
        if (id) {
            $("#modalTitle").text('Chỉnh sửa mặt hàng');
            $('#submit').text('Cập nhật');
        } else {
            $("#modalTitle").text('Thêm mới mặt hàng');
            $('#submit').text('Thêm mới');
        }
    }

    function prependProduct(product) {
        const string = `
            <tr>
                <td>${product.id}</td>
                <td>${product.name}</td>
                <td>${product.category_name}</td>
                <td>${product.price}</td>
                <td>${product.stock}</td>
                <td><img src="/${product.image}" alt="${product.name}" style="width:100px;max-height: 80px"></td>
                <td>
                    <button class="btn btn-primary btn-sm edit" data-id="${product.id}">Chỉnh
                        sửa
                    </button>
                    <button class="btn btn-danger btn-sm delete" data-id="${product.id}">
                        Xóa
                    </button>
                </td>
            </tr>
        `;

        $(".productTable tbody").prepend(string);
    }

    function clearForm() {
        document.querySelector("#name").value = '';
        document.querySelector("#category_id").value = '';
        document.querySelector("#price").value = '';
        document.querySelector("#stock").value = '';
        document.querySelector("#description").value = '';
        document.querySelector("#image").value = '';
    }

    $(document).ready(function () {
        let editImpactor;

        $('#addNewBtn').click(function () {
            renderModalTitle();
            $('#modal').modal('show');
        })

        $('#productForm').submit(function (event) {
            event.preventDefault();

            const id = $("#id").val();
            const name = $("#name").val();
            const categoryId = $("#category_id").val();
            const price = $("#price").val();
            const stock = $("#stock").val();
            const image = $("#image").val();

            const url = id ? '/products/update' : '/products';
            const successMessage = !id ? 'Thêm mới mặt hàng thành công!' : 'Cập nhật mặt hàng thành công';

            if (!name) {
                renderMessage('Mời nhập tên mặt hàng.', 'error');
                return;
            }
            if (!categoryId) {
                renderMessage('Mời chọn loại mặt hàng.', 'error');
                return;
            }
            if (!price) {
                renderMessage('Mời nhập giá cho mặt hàng', 'error');
                return;
            }
            if (!stock) {
                renderMessage('Mời nhập số lượng của mặt hàng', 'error');
                return;
            }
            if (!id && !image) {
                renderMessage('Mời chọn ảnh cho mặt hàng', 'error');
                return;
            }

            const formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.error) {
                        renderMessage(response.error, 'error');
                    } else {
                        if (id) {
                            table.row($(editImpactor).parents('tr')).remove().draw();
                        } else {
                            clearForm();
                        }
                        renderMessage(successMessage);
                        prependProduct(response.product);
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
            document.querySelector('#id').value = id;
            renderModalTitle();

            $.ajax({
                method: 'GET',
                url: '/products/one',
                data: {id},
                success: function (response) {
                    const {product} = JSON.parse(response);
                    document.querySelector("#name").value = product.name;
                    document.querySelector("#category_id").value = product.category_id;
                    document.querySelector("#price").value = product.price;
                    document.querySelector("#stock").value = product.stock;
                    document.querySelector("#description").value = product.description;
                    $('#modal').modal('show');
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
                content: 'Bạn có muốn xóa mặt hàng này không?',
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
                                url: '/products/delete',
                                data: {id},
                                success: function (response) {
                                    $.alert('Xóa mặt hàng thành công');
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

