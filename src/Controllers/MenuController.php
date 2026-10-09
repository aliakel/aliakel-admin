<?php

namespace AliAkel\Admin\Controllers;

use AliAkel\Admin\Form;
use AliAkel\Admin\Layout\Column;
use AliAkel\Admin\Layout\Content;
use AliAkel\Admin\Layout\Row;
use AliAkel\Admin\Tree;
use AliAkel\Admin\Widgets\Box;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title(trans('admin.menu'))
            ->description(trans('admin.list'))
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \AliAkel\Admin\Widgets\Form();
                    $form->action(admin_url('auth/menu'));

                    $menuModel = config('admin.database.menu_model');
                    $permissionModel = config('admin.database.permissions_model');
                    $roleModel = config('admin.database.roles_model');

                    $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
                    $this->titleFields($form);
                    $form->icon('icon', trans('admin.icon'))->default('menu')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('admin.uri'));
                    $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
                    if ((new $menuModel())->withPermission()) {
                        $form->select('permission', trans('admin.permission'))->options($permissionModel::pluck('name', 'slug'));
                    }
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
                });
            });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('admin.auth.menu.edit', ['menu' => $id]);
    }

    /**
     * @return \AliAkel\Admin\Tree
     */
    protected function treeView()
    {
        $menuModel = config('admin.database.menu_model');

        $tree = new Tree(new $menuModel());

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $title = e(admin_menu_title($branch));
            $icon = admin_icon($branch['icon'] ?? '');
            $payload = "{$icon}&nbsp;<strong>{$title}</strong>";

            if (!isset($branch['children'])) {
                if (url()->isValidUrl($branch['uri'])) {
                    $uri = $branch['uri'];
                } else {
                    $uri = admin_url($branch['uri']);
                }

                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
            }

            return $payload;
        });

        return $tree;
    }

    /**
     * Edit interface.
     *
     * @param string  $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title(trans('admin.menu'))
            ->description(trans('admin.edit'))
            ->row($this->form()->edit($id));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $menuModel = config('admin.database.menu_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new $menuModel());

        $form->display('id', 'ID');

        $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
        $this->titleFields($form);
        $form->icon('icon', trans('admin.icon'))->default('menu')->rules('required')->help($this->iconHelp());
        $form->text('uri', trans('admin.uri'));
        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
        if ($form->model()->withPermission()) {
            $form->select('permission', trans('admin.permission'))->options($permissionModel::pluck('name', 'slug'));
        }

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->saving(function (Form $form) {
            $titles = request()->input('titles', []);

            if (!is_array($titles)) {
                $titles = [];
            }

            if (array_filter($titles, fn ($label) => trim((string) $label) !== '') === [] && is_string(request('title')) && request('title') !== '') {
                $titles[admin_menu_primary_locale()] = request('title');
            }

            $form->model()->titles = $titles;
        });

        return $form;
    }

    /**
     * One title input for each enabled language.
     *
     * @param Form|\AliAkel\Admin\Widgets\Form $form
     */
    protected function titleFields($form): void
    {
        $primary = admin_menu_primary_locale();

        foreach (admin_langs() as $code => $label) {
            $field = $form->text('titles->'.$code, trans('admin.title').' ('.$label.')');

            if ($code === $primary) {
                $field->rules('required');
            }
        }
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'Search for a <a href="https://feathericons.com/" target="_blank" rel="noopener">Feather icon</a> and click it. The value stored is the icon name, for example <code>menu</code> or <code>users</code>.';
    }
}
