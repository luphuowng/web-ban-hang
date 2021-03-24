<?php require_once 'partials/head.php' ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">QUẢN LÝ ĐƠN HÀNG</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow-sm" id="addNewBtn">
            <i class="fas fa-plus-circle fa-sm text-white-50"></i>
            Thêm đơn hàng
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered orderTable" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Tên nhân viên</th>
                        <th>Tên khách hàng</th>
                        <th>Ngày đặt hàng</th>
                        <th>Tổng số tiền</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var ArrayObject $orders */
                    foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order->user_name ?></td>
                            <td><?= $order->customer_name ?></td>
                            <td><?= $order->created_at ?></td>
                            <td><?= $order->total ?></td>
                            <td><?= $order->status ?></td>
                            <td>
                                <button class="btn btn-info btn-sm detail" data-id="<?= $order->id ?>">
                                    Chi tiết
                                </button>
                                <button class="btn btn-danger btn-sm delete" data-id="<?= $order->id ?>">
                                    Hủy
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


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalTitle">Chi tiết đơn hàng</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div id="detail-body" class="modal-body text-center">
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="orderForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Thêm mới đơn hàng</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-2" id="formData">
                        <div id="alert-message" class="alert alert-danger alert-dismissible fade show" role="alert"
                             style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="form-group">
                            <label for="customer_id">Khách hàng</label>
                            <select class="form-control" name="customer_id" id="customer_id">
                                <option value="">Chọn khách hàng...</option>
                                <?php /** @var ArrayObject $customers */
                                foreach ($customers as $customer) : ?>
                                    <option value="<?= $customer->id ?>"><?= $customer->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="parentProduct" class="form-row mb-2">
                            <div class="col">
                                <select class="form-control" name="product_id" id="product_id">
                                    <option value="">Chọn mặt hàng...</option>
                                    <?php /** @var ArrayObject $products */
                                    foreach ($products as $product) : ?>
                                        <option value="<?= $product->id ?>"><?= $product->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <input class="form-control" type="number" id="quality" name="quality"
                                       placeholder="Mời nhập số lượng">
                            </div>
                            <div class="col">
                                <button class="btn btn-danger deleteProduct" type="button">Xóa</button>
                            </div>
                        </div>
                        <button id="addProduct" class="btn btn-success btn-block mt-3" type="button">Thêm mặt hàng
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Đóng</button>
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

    function prependOrder(order) {
        const string = `
            <tr>
                <td>${order.user_name}</td>
                <td>${order.customer_name}</td>
                <td>${order.order_time}</td>
                <td>${order.total}</td>
                <td>${order.status}</td>
                <td>
                    <button class="btn btn-danger btn-sm detail" data-id="${order.id}">
                        Chi tiết
                    </button>
                    <button class="btn btn-danger btn-sm delete" data-id="${order.id}">
                        Hủy
                    </button>
                </td>
            </tr>
        `;

        $(".orderTable tbody").prepend(string);
    }

    // function clearForm() {
    //     document.querySelector("#name").value = '';
    //     document.querySelector("#category_id").value = '';
    //     document.querySelector("#price").value = '';
    //     document.querySelector("#stock").value = '';
    //     document.querySelector("#description").value = '';
    //     document.querySelector("#image").value = '';
    // }

    $(document).ready(function () {
        $("#addProduct").click(function () {
            const string = `
            <div class="form-row mb-2">
                ${$("#parentProduct").html()}
            </div>
            `;

            $(string).insertBefore("#addProduct");
        })

        $('#addNewBtn').click(function () {
            $('#modal').modal('show');
        });

        $(".deleteProduct").click(function () {
            // $(this).parents(".form-row").remove()
            alert('This feature does not supported');
        });

        $('#orderForm').submit(function (event) {
            event.preventDefault();

            const customer_id = $("#customer_id").val();
            const product_id = $("#product_id").val();
            const quality = $("#quality").val();

            const productIds = [];
            const qualities = [];
            const productElement = $("select[name=product_id]");
            const qualityElement = $("input[name=quality]");
            productElement.each(function (index) {
                productIds.push($(productElement[index]).val());
            });

            qualityElement.each(function (index) {
                qualities.push($(qualityElement[index]).val());
            });

            const url = '/orders';
            const successMessage = 'Thêm mới đơn hàng thành công!';

            if (!customer_id) {
                renderMessage('Mời chọn khách hàng.', 'error');
                return;
            }

            if (!product_id) {
                renderMessage('Mời chọn sản phẩm', 'error');
                return;
            }

            if (!quality) {
                renderMessage('Mời nhập số lượng cho sản phẩm', 'error');
                return;
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    customer_id,
                    qualities,
                    productIds
                },
                success: function (response) {



                    response = JSON.parse(response);
                    if (response.error) {
                        renderMessage(response.error, 'error');
                    } else {
                        // clearForm();
                        renderMessage(successMessage);
                        prependOrder(response.order);
                    }
                },
                error: function (response) {
                    renderMessage('Server Error', 'error')
                }
            });
        });

        $(".detail").click(function () {
            const id = $(this).data('id');

            $.ajax({
                method: 'GET',
                url: 'orders/one',
                data: {id},
                success: function (response) {
                    console.log(response);
                    const {order_details} = JSON.parse(response);
                    order_details.forEach((orderDetail) => {
                        $("#detail-body").append(`
                        <div class="row">
                            <div class="col-md-6">
                                <img src="/${orderDetail.image}" alt="${orderDetail.name}" class="img-thumbnail">
                            </div>
                            <div class="col-md-6">
                                <h3>Tên mặt hàng: ${orderDetail.name}</h3>
                                <h4>Giá mặt hàng: ${orderDetail.price} vnđ</h4>
                            </div>
                        </div>
                        <hr>
                        `)
                    })
                    $('#detailModal').modal('show');
                },
                error: function (error) {
                    console.log(error)
                }
            });
        })

        $('.delete').click(function () {
            const impactor = this;
            const id = $(impactor).data("id");
            $.confirm({
                title: false,
                content: 'Bạn có muốn xóa đơn hàng này không?',
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
                                url: '/orders/delete',
                                data: {id},
                                success: function (response) {
                                    $.alert('Xóa đơn hàng thành công');
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

