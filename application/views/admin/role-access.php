
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

          <div class="row">
            <div class="col-lg-6">

              <?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

              <h5>Role : <?= $role['role']; ?> </h5>

              <?= $this->session->flashdata('message'); ?>

              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Role</th>
                    <th scope="col">Access</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1; ?>
                  <?php foreach ($menu as $m) : ?>
                  <tr>
                    <th scope="row"><?= $i; ?></th>
                    <td><?= $m['menu']; ?></td>
                    <td> 
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" <?= check_access($role['id_role'], $m['id_user_menu']); ?> data-role="<?= $role['id_role']; ?>" data-menu="<?= $m['id_user_menu']; ?>">
                        </div>
                    </td>
                  </tr>
                  <?php $i++ ; ?>
                  <?php endforeach; ?>
                </tbody>
              </table>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

     