<?php $__env->startSection('content'); ?>
    <!-- Login Page -->
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
                                        <p class="text-center mt-3" style="color: rgb(3, 73, 3)">Please, login.</p>
                                        <div class="card-body">

                                            <form method="POST" action="<?php echo e(route('login')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="form-floating mb-3">
                                                    <input style="border: thin solid darkgreen;" type="email"
                                                        class="form-control" id="emailInput" placeholder="name@example.com"
                                                        name="email" value="<?php echo e(old('email')); ?>" required autofocus
                                                        autocomplete="email">
                                                    <label for="emailInput"><?php echo e(__('Email address')); ?></label>
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

                                                <div class="form-floating mb-4 position-relative">
                                                    <input style="border: thin solid darkgreen;" type="password"
                                                        class="form-control" id="passwordInput" placeholder="Password"
                                                        name="password" required autocomplete="current-password">
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


                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="rememberMe"
                                                        style="border: thin solid darkgreen;" name="remember">
                                                    <label class="form-check-label"
                                                        for="rememberMe"><?php echo e(__('Remember me')); ?></label>
                                                </div>
                                                <div class="form-group mt-2 mb-4">
                                                    <?php if(Route::has('password.request')): ?>
                                                    <a class="text-primary text-decoration-none"
                                                        href="<?php echo e(route('password.request')); ?>">
                                                        <?php echo e(__('Forgot your password?')); ?>

                                                    </a>
                                                <?php endif; ?>
                                                </div>

                                               

                                                <button type="submit"
                                                    class="btn btn-primary w-100"><?php echo e(__('Log in')); ?></button>
                                            </form>



                                            <div class="auth-footer">
                                                Don't have an account? <a href="<?php echo e(route('register')); ?>"
                                                    class="text-primary text-decoration-none">Register</a>.
                                            </div>
                                            
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom-2"
                                style="border: thin solid rgb(203, 214, 203)">
                                <div class="text-white px-3 py-0 p-md-5 mx-md-4">
                                    <img src="<?php echo e(asset('/dashboard/images/produce_home_350.jpg')); ?>"
                                        alt="agric_produce_home">
                                    <h4 class="mb-4">&nbsp;</h4>
                                    <p class="small mb-0" style="color: black">Now that you have your account registered
                                        with us, you can log in to gain access to our wide range of services.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.loginregister', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp\www\BDIC\biams\resources\views/auth/login.blade.php ENDPATH**/ ?>