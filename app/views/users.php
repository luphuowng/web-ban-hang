<?php require_once 'partials/head.php' ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nhân viên</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow-sm" id="addUserBtn">
            <i class="fas fa-plus-circle fa-sm text-white-50"></i>
            Thêm nhân viên
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách nhân viên</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered userTable" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Họ Tên</th>
                        <th>Email</th>
                        <th>Vị Trí</th>
                        <th>Địa Chỉ</th>
                        <th>Số Điện Thoại</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var ArrayObject $users */
                    foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->name ?></td>
                            <td><?= $user->email ?></td>
                            <td><?= $user->role ?></td>
                            <td><?= $user->address ?></td>
                            <td><?= $user->phone ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm edit" data-id="<?= $user->id ?>">Chỉnh
                                    sửa
                                </button>
                                <button class="btn btn-danger btn-sm delete" data-id="<?= $user->id ?>">
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
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Thêm mới nhân viên</h5>
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
                        <input type="hidden" name="userId" id="userId">
                        <div class="form-group">
                            <label for="name">Tên nhân viên</label>
                            <input type="text" class="form-control" id="name" placeholder="Nhập tên nhân viên">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Nhập email nhân viên">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label for="password">Mật khẩu</label>
                                <input type="email" class="form-control" id="password" placeholder="Nhập mật khẩu">
                            </div>
                            <div class="col-sm-6">
                                <label for="repeat-password">Nhập lại mật khẩu</label>
                                <input type="password" class="form-control" id="repeat-password"
                                       placeholder="Nhập lại mật khẩu">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role">Vị Trí</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">Chọn vị trí...</option>
                                <option value="Quản Lý">Quản Lý</option>
                                <option value="Nhân viên">Nhân viên</option>
                            </select>
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
                <button class="btn btn-primary" id="userSubmit" type="button">Thêm</button>
            </div>
        </div>
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
        const id = $("#userId").val();
        if (id) {
            $("#userModalTitle").text('Chỉnh sửa nhân viên');
            $('#userSubmit').text('Cập nhật');
        } else {
            $("#userModalTitle").text('Thêm mới nhân viên');
            $('#userSubmit').text('Thêm mới');
        }
    }

    function prependUser(user) {
        const string = `
            <tr>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.role}</td>
                <td>${user.address}</td>
                <td>${user.phone}</td>
                <td>
                    <button class="btn btn-primary btn-sm edit" data-id="${user.id}">Chỉnh
                        sửa
                    </button>
                    <button class="btn btn-danger btn-sm delete" data-id="${user.id}">
                        Xóa
                    </button>
                </td>
            </tr>
        `;

        $(".userTable tbody").prepend(string);
    }

    function clearForm() {
        document.querySelector("#name").value = '';
        document.querySelector("#email").value = '';
        document.querySelector("#password").value = '';
        document.querySelector("#repeat-password").value = '';
        document.querySelector("#role").value = '';
        document.querySelector("#address").value = '';
        document.querySelector("#phone").value = '';
    }

    $(document).ready(function () {
        let editImpactor;

        $('#addUserBtn').click(function () {
            renderModalTitle();
            $('#userModal').modal('show');
        })

        $('#userSubmit').click(function (event) {
            const id = $("#userId").val();
            const name = $("#name").val();
            const email = $("#email").val();
            const password = $("#password").val();
            const repeatPassword = $("#repeat-password").val();
            const role = $("#role").val();
            const address = $("#address").val();
            const phone = $("#phone").val();

            const url = id ? '/users/update' : '/users';
            const successMessage = !id ? 'Thêm mới nhân viên thành công!' : 'Cập nhật nhân viên thành công';

            if (!name) {
                console.log(name)
                renderMessage('Không được để trống tên của nhân viên', 'error');
                return;
            }
            if (!email) {
                renderMessage('Không được để trống email của nhân viên', 'error');
                return;
            }
            if (!id) {
                if (!password) {
                    renderMessage('Không được để trống mật khẩu của nhân', 'error');
                    return;
                }
                if (!repeatPassword || password !== repeatPassword) {
                    renderMessage('Mật lại mật khẩu không khớp với mật khẩu', 'error');
                    return;
                }
            }
            if (!address) {
                renderMessage('Không được để trống địa chỉ của nhân viên', 'error');
                return;
            }
            if (!role) {
                renderMessage('Mời điền thông tin vị trí cho nhân viên', 'error');
                return;
            }
            if (!phone) {
                renderMessage('Không được để trống số điện thoại của nhân viên', 'error');
                return;
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: {id, name, email, role, password, address, phone},
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
                        prependUser(response.user);
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
            document.querySelector('#userId').value = id;
            renderModalTitle();
            $('#userModal').modal('show');

            $.ajax({
                method: 'GET',
                url: '/users/one',
                data: {id},
                success: function (response) {
                    const {user} = JSON.parse(response);
                    document.querySelector("#name").value = user.name;
                    document.querySelector("#email").value = user.email;
                    document.querySelector("#role").value = user.role;
                    document.querySelector("#address").value = user.address;
                    document.querySelector("#phone").value = user.phone;
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
                content: 'Bạn có muốn xóa nhân viên này không?',
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
                                url: '/users/delete',
                                data: {id},
                                success: function (response) {
                                    $.alert('Xóa nhân viên thành công');
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

