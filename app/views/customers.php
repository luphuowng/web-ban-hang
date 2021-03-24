<?php require_once 'partials/head.php' ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Khách hàng</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow-sm" id="addCustomerBtn">
            <i class="fas fa-plus-circle fa-sm text-white-50"></i>
            Thêm khách hàng
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách khách hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered customerTable" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Họ Tên</th>
                        <th>Email</th>
                        <th>Địa Chỉ</th>
                        <th>Số Điện Thoại</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var ArrayObject $customers */
                    foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= $customer->name ?></td>
                            <td><?= $customer->email ?></td>
                            <td><?= $customer->address ?></td>
                            <td><?= $customer->phone ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm edit" data-id="<?= $customer->id ?>">Chỉnh
                                    sửa
                                </button>
                                <button class="btn btn-danger btn-sm delete" data-id="<?= $customer->id ?>">
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
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalTitle">Thêm mới khách hàng</h5>
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
                        <input type="hidden" name="customerId" id="customerId">
                        <div class="form-group">
                            <label for="name">Tên khách hàng</label>
                            <input type="text" class="form-control" id="name" placeholder="Nhập tên khách hàng">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Nhập email khách hàng">
                        </div>

                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" class="form-control" id="address" placeholder="Nhập địa chỉ">
                        </div>

                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" placeholder="Nhập số điện thoại">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Hủy</button>
                <button class="btn btn-primary" id="customerSubmit" type="button">Thêm</button>
            </div>
        </div>
    </div>
</div>

<?php require_once 'partials/scripts.php' ?>

<script>
    function renderModalTitle() {
        const id = $("#customerId").val();
        if (id) {
            $("#customerModalTitle").text('Chỉnh sửa khách hàng');
            $('#customerSubmit').text('Cập nhật');
        } else {
            $("#customerModalTitle").text('Thêm mới khách hàng');
            $('#customerSubmit').text('Thêm mới');
        }
    }

    function prependCustomer(customer) {
        const string = `
            <tr>
                <td>${customer.name}</td>
                <td>${customer.email}</td>
                <td>${customer.address}</td>
                <td>${customer.phone}</td>
                <td>
                    <button class="btn btn-primary btn-sm edit" data-id="${customer.id}">Chỉnh
                        sửa
                    </button>
                    <button class="btn btn-danger btn-sm delete" data-id="${customer.id}">
                        Xóa
                    </button>
                </td>
            </tr>
        `;

        $(".customerTable tbody").prepend(string);
    }

    function clearForm() {
        document.querySelector("#name").value = '';
        document.querySelector("#email").value = '';
        document.querySelector("#address").value = '';
        document.querySelector("#phone").value = '';
    }

    $(document).ready(function () {
        let editImpactor;

        $('#addCustomerBtn').click(function () {
            renderModalTitle();
            $('#customerModal').modal('show');
        })

        $('#customerSubmit').click(function (event) {
            const id = $("#customerId").val();
            const name = $("#name").val();
            const email = $("#email").val();
            const address = $("#address").val();
            const phone = $("#phone").val();

            const url = id ? '/customers/update' : '/customers';
            const successMessage = !id ? 'Thêm mới khách hàng thành công!' : 'Cập nhật khách hàng thành công';

            if (!name) {
                console.log(name)
                renderMessage('Không được để trống tên của khách hàng', 'error');
                return;
            }
            if (!email) {
                renderMessage('Không được để trống email của khách hàng', 'error');
                return;
            }
            if (!address) {
                renderMessage('Không được để trống địa chỉ của khách hàng', 'error');
                return;
            }
            if (!phone) {
                renderMessage('Không được để trống số điện thoại của khách hàng', 'error');
                return;
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: {id, name, email, address, phone},
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.error) {
                        renderMessage(response.error, 'error');
                    } else {
                        if (id) {
                            table.row($(editImpactor).parents('tr')).remove().draw();
                            prependCustomer({id, name, email, address, phone});
                            renderMessage(successMessage);
                        } else {
                            renderMessage(successMessage);
                            prependCustomer(response.customer);
                            clearForm();
                        }
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
            document.querySelector('#customerId').value = id;
            renderModalTitle();
            $('#customerModal').modal('show');

            $.ajax({
                method: 'GET',
                url: '/customers/one',
                data: {id},
                success: function (response) {
                    const {customer} = JSON.parse(response);
                    document.querySelector("#name").value = customer.name;
                    document.querySelector("#email").value = customer.email;
                    document.querySelector("#address").value = customer.address;
                    document.querySelector("#phone").value = customer.phone;
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
                content: 'Bạn có muốn xóa khách hàng này không?',
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
                                url: '/customers/delete',
                                data: {id},
                                success: function (response) {
                                    $.alert('Xóa khách hàng thành công');
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

