<div class="row mx-0" ng-show="oxyPowerPack.currentTab=='wordpress'">
    <div class="col-sm-2 p-0">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0">
            <div class="card-header rounded-0 py-2">
                Pages
                <span class="badge badge-secondary new-page-button" ng-click="oxyPowerPack.pages.newElement()">+</span>
            </div>
            <div class="card-body p-0 post-list-container">
                <div class="list-group border-0 post-list">
                                <span ng-if="!oxyPowerPack.pages.fetching" ng-repeat="page in oxyPowerPack.pages.collection.models" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-class="{'active': page.attributes.id == oxyPowerPack.currentPostObject.attributes.id}" ng-click="oxyPowerPack.currentPostObject = page">
                                    {{page.attributes.title.rendered == '' ? '(no title)':page.attributes.title.rendered}}
                                </span>
                    <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-if="oxyPowerPack.pages.fetching">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>
                <ul class="pagination pagination-sm post-list-paginator m-0">
                    <li class="page-item" ng-class="{'disabled': oxyPowerPack.pages.fetching || oxyPowerPack.pages.collection.state.currentPage == 1 }">
                        <span class="page-link border-dark rounded-0" ng-click="oxyPowerPack.pages.changePage(-1)">&laquo; Prev</span>
                    </li>
                    <li class="page-item active disabled post-list-paginator-status">
                        <span class="page-link border-dark rounded-0">{{oxyPowerPack.pages.collection.state.currentPage}}/{{oxyPowerPack.pages.collection.state.totalPages}}</span>
                    </li>
                    <li class="page-item" ng-class="{'disabled': oxyPowerPack.pages.fetching || oxyPowerPack.pages.collection.state.currentPage == oxyPowerPack.pages.collection.state.totalPages }">
                        <span class="page-link border-dark rounded-0" ng-click="oxyPowerPack.pages.changePage(1)">Next &raquo;</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-2 p-0">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0">
            <div class="card-header rounded-0 py-2">Templates</div>
            <div class="card-body p-0 post-list-container">
                <div class="list-group border-0 post-list">
                    <a href="#" ng-if="!oxyPowerPack.templates.fetching" ng-repeat="template in oxyPowerPack.templates.collection.models" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-class="{'active': template.attributes.id == oxyPowerPack.currentPostObject.attributes.id}" ng-click="oxyPowerPack.currentPostObject = template">
                        {{template.attributes.title.rendered == '' ? '(no title)':template.attributes.title.rendered}}
                        <span ng-if="template.attributes.meta.ct_template_type == 'reusable_part'" class="badge badge-primary badge-pill">Reusable</span>
                    </a>
                    <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-if="oxyPowerPack.templates.fetching">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>
                <ul class="pagination pagination-sm post-list-paginator m-0">
                    <li class="page-item" ng-class="{'disabled': oxyPowerPack.templates.fetching || oxyPowerPack.templates.collection.state.currentPage == 1 }">
                        <a class="page-link border-dark rounded-0" href="#" ng-click="oxyPowerPack.templates.changePage(-1)">&laquo; Prev</a>
                    </li>
                    <li class="page-item active disabled post-list-paginator-status">
                        <a class="page-link border-dark rounded-0" href="#">{{oxyPowerPack.templates.collection.state.currentPage}}/{{oxyPowerPack.templates.collection.state.totalPages}}</a>
                    </li>
                    <li class="page-item" ng-class="{'disabled': oxyPowerPack.templates.fetching || oxyPowerPack.templates.collection.state.currentPage == oxyPowerPack.templates.collection.state.totalPages }">
                        <a class="page-link border-dark rounded-0" href="#" ng-click="oxyPowerPack.templates.changePage(1)">Next &raquo;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-2 p-0">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0">
            <div class="card-header rounded-0 py-2">
                Posts
                <span class="badge badge-secondary new-page-button" ng-click="oxyPowerPack.posts.newElement()">+</span>
            </div>
            <div class="card-body p-0 post-list-container">
                <div class="list-group border-0 post-list">
                    <a href="#" ng-if="!oxyPowerPack.posts.fetching" ng-repeat="post in oxyPowerPack.posts.collection.models" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-class="{'active': post.attributes.id == oxyPowerPack.currentPostObject.attributes.id}" ng-click="oxyPowerPack.currentPostObject = post">
                        {{post.attributes.title.rendered == '' ? '(no title)':post.attributes.title.rendered}}
                    </a>
                    <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-if="oxyPowerPack.posts.fetching">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>
                <ul class="pagination pagination-sm post-list-paginator m-0">
                    <li class="page-item" ng-class="{'disabled': oxyPowerPack.posts.fetching || oxyPowerPack.posts.collection.state.currentPage == 1 }">
                        <a class="page-link border-dark rounded-0" href="#" ng-click="oxyPowerPack.posts.changePage(-1)">&laquo; Prev</a>
                    </li>
                    <li class="page-item active disabled post-list-paginator-status">
                        <a class="page-link border-dark rounded-0" href="#">{{oxyPowerPack.posts.collection.state.currentPage}}/{{oxyPowerPack.posts.collection.state.totalPages}}</a>
                    </li>
                    <li class="page-item" ng-class="{'disabled': oxyPowerPack.posts.fetching || oxyPowerPack.posts.collection.state.currentPage == oxyPowerPack.posts.collection.state.totalPages }">
                        <a class="page-link border-dark rounded-0" href="#" ng-click="oxyPowerPack.posts.changePage(1)">Next &raquo;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-2 p-0" ng-if="oxyPowerPack.library_enabled">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0">
            <div class="card-header rounded-0 py-2">
                Library / Blocks
                <span class="badge badge-secondary new-page-button" ng-click="oxyPowerPack.blocks.newElement()">+</span>
            </div>
            <div class="card-body p-0 post-list-container">
                <div class="list-group border-0 post-list">
                    <a href="#" ng-if="!oxyPowerPack.blocks.fetching" ng-repeat="block in oxyPowerPack.blocks.collection.models" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-class="{'active': block.attributes.id == oxyPowerPack.currentPostObject.attributes.id}" ng-click="oxyPowerPack.currentPostObject = block">
                        {{block.attributes.title.rendered == '' ? '(no title)':block.attributes.title.rendered}}
                    </a>
                    <div class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0" ng-if="oxyPowerPack.blocks.fetching">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>
                <ul class="pagination pagination-sm post-list-paginator m-0">
                    <li class="page-item" ng-class="{'disabled': oxyPowerPack.blocks.fetching || oxyPowerPack.blocks.collection.state.currentPage == 1 }">
                        <a class="page-link border-dark rounded-0" href="#" ng-click="oxyPowerPack.blocks.changePage(-1)">&laquo; Prev</a>
                    </li>
                    <li class="page-item active disabled post-list-paginator-status">
                        <a class="page-link border-dark rounded-0" href="#">{{oxyPowerPack.blocks.collection.state.currentPage}}/{{oxyPowerPack.blocks.collection.state.totalPages}}</a>
                    </li>
                    <li class="page-item" ng-class="{'disabled': oxyPowerPack.blocks.fetching || oxyPowerPack.blocks.collection.state.currentPage == oxyPowerPack.blocks.collection.state.totalPages }">
                        <a class="page-link border-dark rounded-0" href="#" ng-click="oxyPowerPack.blocks.changePage(1)">Next &raquo;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-4 p-0" ng-if="!!oxyPowerPack.currentPostObject">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0">
            <div class="card-header rounded-0 py-2">
                {{oxyPowerPack.currentPostObject.attributes.title.rendered}} ({{ oxyPowerPack.currentPostObject.attributes.type }}{{ oxyPowerPack.currentPostObject.attributes.type == 'ct_template' && oxyPowerPack.currentPostObject.attributes.meta.ct_template_type  == 'reusable_part' ? ', reusable part' : '' }})
                <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.currentPostObject=null">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body p-2 edit-post-container">
                <div class="post-action-buttons">
                    <button type="button" class="btn btn-primary btn-sm mb-1 rounded-0" ng-click="oxyPowerPack.changePostTitle()">Change Title</button>
                    <button type="button" class="btn btn-primary btn-sm mb-1 rounded-0" ng-click="oxyPowerPack.openIn('oxygen')">Oxygen Builder</button>
                    <button type="button" class="btn btn-primary btn-sm mb-1 rounded-0" ng-click="oxyPowerPack.openIn('dashboard')">WordPress Dashboard</button>
                    <button ng-if="oxyPowerPack.currentPostObject.attributes.type == 'page'" type="button" class="btn btn-primary btn-sm rounded-0" ng-click="oxyPowerPack.openIn('frontend')">Frontend</button>
                </div>
                <pre class="builder-shortcodes m-0 ml-2 bg-dark">{{oxyPowerPack.currentPostObject.attributes.meta.ct_builder_shortcodes}}</pre>
            </div>
        </div>
    </div>
    <div class="col-sm-4 p-0" ng-if="!oxyPowerPack.currentPostObject">
        <div class="card border-primary rounded-0 border-top-0 border-bottom-0 border-right-0">
            <div class="card-header rounded-0 py-2">
                Admin Menu
            </div>
            <div class="card-body p-0">
                <div class="row m-0 p-0">
                    <div class="col-sm-6 m-0 p-0">
                        <div class="list-group border-0 post-list admin-menu-list">
                                        <span ng-repeat="menuItem in oxyPowerPack.adminMenu" ng-click="oxyPowerPack.currentMenuItem = menuItem" ng-class="{'active': oxyPowerPack.currentMenuItem == menuItem}" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                                            {{menuItem.title}}
                                        </span>
                        </div>
                    </div>
                    <div class="col-sm-6 m-0 p-0">
                        <div class="list-group border-0 post-list admin-menu-list">
                            <a href="{{entry.url}}" ng-repeat="entry in oxyPowerPack.currentMenuItem.entries" class="list-group-item list-group-item-action border-left-0 border-right-0 rounded-0 border-dark py-0">
                                {{entry.title}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
