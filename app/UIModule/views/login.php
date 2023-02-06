<?php $this->extends('@ui:layout') ?>

<?php $this->block('form') ?>
    <form method="post">
        <!-- Email input -->
        <div class="mb-6">
            <input
                    type="text"
                    class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                    name="email"
                    placeholder="Email address"
                    value="<?= $request->old('email', $request->query('email', '')) ?>"
                    autofocus
            />
            <?php if($request->hasFlash('email')): ?>
                <small class="text-red-500"><?= $request->flash('email') ?></small>
            <?php endif ?>
        </div>

        <!-- Password input -->
        <div class="mb-6">
            <input
                    type="password"
                    class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                    name="password"
                    placeholder="Password"
            />
            <?php if($request->hasFlash('password')): ?>
                <small class="text-red-500"><?= $request->flash('password') ?></small>
            <?php endif ?>
        </div>

        <div class="flex justify-between items-start mb-6">
            <div>
                <button type="submit"
                        class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                >
                    Login
                </button>
                <p class="text-sm font-semibold mt-2 pt-1 mb-0">
                    Don't have an account?
                    <a href="<?= router('users.create') ?>"
                       class="text-red-600 hover:text-red-700 focus:text-red-700 transition duration-200 ease-in-out"
                    >Register</a>
                </p>
            </div>
            <a href="<?= router('ui.forgot_password') ?>" class="text-gray-800 hover:text-blue-600">Forgot password?</a>
        </div>
    </form>
<?php $this->endblock() ?>