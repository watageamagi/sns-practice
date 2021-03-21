
export default {
    menu: [
        {
            title: 'HOME',
            icon: 'tachometer-alt',
            path: '/admin',
            // iconColor: 'red',
        },
        {
            title: 'サーバー操作',
            icon: 'server',
            path: '/admin/server-operation',
            children: [
                {
                    title: 'メンテナンスモード',
                    icon: 'ban',
                    path: '/admin/server-operation/maintenance-mode',
                },
            ]
        },
        {
            title: 'ユーザー管理',
            icon: 'list',
            path: '/admin/users',
            query: {paginate: 15}
        },
    ],
}
