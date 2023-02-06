<?php $this->extends('layout') ?>

<?php $this->block('content') ?>
    <section class="mx-auto lg:px-20">
        <div class="h-full text-gray-800">
            <div
                    class="flex xl:justify-center lg:justify-between justify-center items-center flex-wrap h-full g-6"
            >
                <div
                        class="grow-0 shrink-1 md:shrink-0 basis-auto lg:w-6/12 w-9/12"
                >
                    <img
                            src="<?= asset('img/mky.png') ?>"
                            class="w-full"
                            alt="Sample image"
                    />
                </div>
                <div class="xl:ml-20 xl:w-5/12 lg:w-5/12 md:w-8/12 mb-12 md:mb-0">
                    <?php if($request->hasFlash('success')): ?>
                        <p class="py-3 px-2 mb-6 text-center rounded bg-green-100 text-green-700"><?= $request->flash('success') ?></p>
                    <?php endif ?>
                    <?php if($request->hasFlash('error')): ?>
                        <p class="py-3 px-2 mb-6 text-center rounded bg-red-100 text-red-700"><?= $request->flash('error') ?></p>
                    <?php endif ?>
                    <?php if($request->hasFlash('form')): ?>
                        <p class="py-3 px-2 mb-6 text-center rounded bg-red-100 text-red-700"><?= $request->flash('form') ?></p>
                    <?php endif ?>
                    <?= $this->section('form') ?>
                </div>
            </div>
        </div>
    </section>
<?php $this->endblock() ?>