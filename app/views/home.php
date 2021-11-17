<main class="main ss-container ss-shadow-2">
    <!-- Documentation -->
    <section class="section doc">
        <div class="section__header">
            <span class="section__header__icon material-icons">menu_book</span>
            <a href="https://stacksync.netlify.app/" class="section__header__title">Documentation</a>
        </div>
        <div class="section__body">
            <div class="section__body__content">
                <p><a href="https://stacksync.netlify.app/">StackSync</a> is a simple, lightweight and native fullstack mini-framework.</p>
                <p>Backend with PHP, based on the MVC design pattern, a custom API and Views system.</p>
                <p>Frontend with a <a href="https://github.com/KhomsiAdam/SASS-Architecture">SASS architecture</a> based on the <a href="https://sass-guidelin.es/">7-1 architecture</a>, the <a href="http://getbem.com/">BEM</a> methodology and JavaScript's native ES6 Modules.</p>
                <p>Make sure to read through the <a href="https://stacksync.netlify.app/">documentation</a> that covers every aspect of the mini-framework.</p>
            </div>
        </div>
        <div class="section__footer">
            <a href="https://stacksync.netlify.app/setup.html" class="section__footer__link">Setup</a>
            <a href="https://stacksync.netlify.app/directories.html" class="section__footer__link">Directory Structure</a>
            <a href="https://stacksync.netlify.app/advanced.html" class="section__footer__link">Advanced</a>
            <a href="https://stacksync.netlify.app/commands.html" class="section__footer__link">Commands</a>
        </div>
    </section>
    <!-- Collaborations -->
    <section class="section collab">
        <div class="section__header">
            <span class="section__header__icon material-icons">precision_manufacturing</span>
            <a href="https://github.com/KhomsiAdam/StackSync" class="section__header__title">Contributions</a>
        </div>
        <div class="section__body">
            <div class="section__body__content">
                <p>Please open an <a href="https://github.com/KhomsiAdam/StackSync/issues">issue</a> first to discuss any fixes or improvements before issuing a <a href="https://github.com/KhomsiAdam/StackSync/pulls">pull request</a>. Make sure to create a branch for your fix or improvement with documentation.</p>
                <p>The <a href="https://github.com/KhomsiAdam/SASS-Architecture">SASS Architecture</a> (along with the use of JavaScript's native ES6 modules) are optional and independent. Feel free to work the frontend side with your prefered methods. If used, please refer to it's separate <a href="https://github.com/KhomsiAdam/SASS-Architecture">repository</a> for more details.</p>
            </div>
        </div>
        <div class="section__footer">
            <a href="https://github.com/KhomsiAdam/StackSync" class="section__footer__link">Repository</a>
            <a href="https://github.com/KhomsiAdam/StackSync/issues" class="section__footer__link">Issues</a>
            <a href="https://github.com/KhomsiAdam/StackSync/pulls" class="section__footer__link">Pull requests</a>
            <a href="https://github.com/KhomsiAdam/SASS-Architecture" class="section__footer__link">SASS Architecture</a>
        </div>
    </section>
    <!-- Features -->
    <section class="section features">
        <div class="section__header">
            <span class="section__header__icon material-icons">list_alt</span>
            <a href="https://stacksync.netlify.app/" class="section__header__title">Features</a>
        </div>
        <div class="section__body">
            <div class="section__body__content">
                <div class="section__body__content__element p-right p-bottom">
                    <p><a href="https://stacksync.netlify.app/advanced.html#api">Custom API system: </a></p>
                    <p>The API is designed to have one endpoint (that can be setup with routing) for each controller/model and therefore table in the database. The data sent in JSON format must contain the method requested and the parameters if required.</p>
                    <p>Authentication is token based using <a href="https://jwt.io/introduction">JWT</a>'s php implementation: <a href="https://github.com/firebase/php-jwt">php-jwt</a>.</p>
                    <p>Loading of environment variables is with the help of <a href="https://github.com/vlucas/phpdotenv">phpdotenv</a>.</p>
                </div>
                <div class="section__body__content__element p-left p-bottom">
                    <p><a href="https://stacksync.netlify.app/advanced.html#routing">Simple flexible routing: </a></p>
                    <p>Adding a new route is simple, and it's name is unrelated to its functionality. Any route can be setup to call a chosen controller and it's method.</p>
                    <p>Web routes are either requested with a GET or POST method. GET routes are for views and must call the WebController. POST routes will need custom made controllers.</p>
                    <p>API routes are POST only and must call the api controller needed with it's processing function to invoke the requested method.</p>
                </div>
                <div class="section__body__content__element p-right p-bottom">
                    <p><a href="https://stacksync.netlify.app/advanced.html#views">Components based views: </a></p>
                    <p>Views are managed with a templating system that makes it possible to pick a chosen layout for a view.</p>
                    <p>Layouts contain static components that are destined to be present in all pages using a specific layout. Each layout has a content component which is the actual rendered view.</p>
                    <p>The content which is your actual requested view, (with a defined route) can contain dynamic components which are not certainly present in all pages with a defined layout but are in the requested view.</p>
                </div>
                <div class="section__body__content__element p-left p-bottom">
                    <p><a href="https://stacksync.netlify.app/commands.html">Composer custom scripts:</a></p>
                    <p>With the help of <a href="https://getcomposer.org/">composer</a>. It is possible to execute various helpful commands and also create files automatically based on templates.</p>
                    <p>These files can be migrations, seeders and controllers (with their model).</p>
                    <p>Make sure to refer to the commands available make yourself familiar with using them.</p>
                    <p>All the namespaces and classes are autoloaded by <a href="https://getcomposer.org/">composer</a> using the <a href="https://www.php-fig.org/psr/psr-4/">PSR-4 autoloading standard</a>.</p>
                </div>
            </div>
        </div>
        <div class="section__footer">
            <a href="https://stacksync.netlify.app/advanced.html#api" class="section__footer__link">API</a>
            <a href="https://stacksync.netlify.app/advanced.html#controllers" class="section__footer__link">Controllers</a>
            <a href="https://stacksync.netlify.app/advanced.html#routing" class="section__footer__link">Routing</a>
            <a href="https://stacksync.netlify.app/advanced.html#views" class="section__footer__link">Views</a>
            <a href="https://stacksync.netlify.app/advanced.html#migrations" class="section__footer__link">Migrations</a>
            <a href="https://stacksync.netlify.app/advanced.html#seeding" class="section__footer__link">Seeding</a>
        </div>
    </section>
</main>