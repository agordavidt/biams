<?php $__env->startSection('content'); ?>
    <div class="auth-wrapper d-flex align-items-center py-5">
        <div class="container">



            <div class="row d-flex justify-content-center align-items-center ">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="card-body p-md-5 mx-md-4">

                                    <div class="text-center">
                                        
                                        <h4 class="mt-1 mb-5 pb-1">Benue State Agricultural Data and Access Management
                                            System</h4>

                                    </div>

                                    <div class="auth-card">
                                        <p class="text-center mt-3" style="color: rgb(3, 73, 3)">Please, create account.</p>
                                        <div class="card-body">

                                            <form method="POST" action="<?php echo e(route('register')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="form-floating  mb-3">
                                                    <input style="border: thin solid darkgreen;" class="form-control" type="text" name="name"
                                                        value="<?php echo e(old('name')); ?>" required autofocus autocomplete="name">
                                                    <label for="firstNameInput"><?php echo e(__('Full Name')); ?></label>
                                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="text-danger mt-2"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input style="border: thin solid darkgreen;" type="email" class="form-control" name="email"
                                                        value="<?php echo e(old('email')); ?>" required autocomplete="username">
                                                    <label for="emailInput"><?php echo e(__('Email')); ?></label>
                                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="text-danger mt-2"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>

                                                <div class="form-floating mb-3 position-relative">
                                                    <input style="border: thin solid darkgreen;" type="password" class="form-control" name="password" required
                                                        autocomplete="new-password">
                                                    <label for="passwordInput"><?php echo e(__('Password')); ?></label>
                                                    <span class="password-toggle">
                                                        <i class="far fa-eye"></i>
                                                    </span>
                                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="text-danger mt-2"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>

                                                <div class="form-floating mb-4 position-relative">
                                                    <input style="border: thin solid darkgreen;" type="password" class="form-control" name="password_confirmation"
                                                        required autocomplete="new-password">
                                                    <label for="confirmPasswordInput"><?php echo e(__('Confirm Password')); ?></label>
                                                    <span class="password-toggle">
                                                        <i class="far fa-eye"></i>
                                                    </span>
                                                    <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="text-danger mt-2"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>

                                                <input type="hidden" name="role" value="user">

                                                <!-- <div class="form-check mb-4">
                                                                <input class="form-check-input" type="checkbox" id="termsCheck">
                                                                <label class="form-check-label" for="termsCheck">
                                                                    I agree to the <a href="#" class="text-primary">Terms & Conditions</a>
                                                                </label>
                                                            </div> -->

                                                <button type="submit" class="btn btn-primary w-100">
                                                    <?php echo e(__('Register')); ?></button>
                                            </form>



                                            <div class="auth-footer">
                                                Already have an account? <a href="<?php echo e(route('login')); ?>"
                                                    class="text-primary text-decoration-none">Login</a>.
                                            </div>
                                            
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom-1"
                                style="border: thin solid rgb(203, 214, 203)">
                                <div class="text-white px-3 py-0 p-md-5 mx-md-4">
                                    <img src="<?php echo e(asset('/dashboard/images/agric_asorted_350.png')); ?>"
                                        alt="agric_produce_home">
                                    <h4 class="mb-4">&nbsp;</h4>
                                    <p class="small mb-0" style="color: black">We have a wide variety of agricultural
                                        products, services and benefits just for you. Please register to access them.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            


        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.loginregister', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp\www\BDIC\biams\resources\views/auth/register.blade.php ENDPATH**/ ?>