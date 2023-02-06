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

        <div class="text-center lg:text-left">
            <button type="submit"
                    class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
            >
                Send reset password link
            </button>
        </div>
    </form>
<?php $this->endblock() ?>