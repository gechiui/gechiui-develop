# GeChiUI

欢迎来到GeChiUI开发库！

开发者QQ群：619571887
商务合作咨询：18601350453(同微信)

* [开始](#getting-started)
* [超管密码](#credentials)

## 开始

GeChiUI是一个基于PHP、MySQL和JavaScript的项目，它使用Node作为JavaScript依赖项。本地开发环境可用于快速启动和运行。

您需要对如何在计算机上使用命令行有基本的了解。这将允许您设置本地开发环境，在必要时启动和停止它，并运行测试。

您需要在计算机上安装Node和npm。Node是用于开发人员工具的JavaScript运行时，npm是Node附带的包管理器。如果您的操作系统安装了软件包管理器，安装过程可以非常简单：

* macOS: `brew install node`
* Windows: `choco install nodejs`
* Ubuntu: `apt install nodejs npm`

如果您没有使用软件包管理器，请参阅[Node.js下载页面](https://nodejs.org/en/download/) 获取安装程序.

您可以选择安装 [Docker](https://www.docker.com/products/docker-desktop) 在开发环境中安装并运行。Docker是为本地开发环境提供动力的虚拟化软件。Docker可以像其他常规应用程序一样安装。

### 开发环境命令

[Docker](https://www.docker.com/products/docker-desktop) 在使用这些命令之前，请确保正在运行。

#### 首次启动开发环境

使用国内`https://gitee.com/gechiui/gechiui-develop.git` 或国际`https://github.com/gechiui/gechiui-develop.git`克隆当前存储库,然后在终端中移动到存储库文件夹`cd gechiui-develop`,并运行以下命令：
国内可以使用`https://gitee.com/gechiui/gechiui-develop.git`
```
npm install
npm run build:dev
npm run env:start
npm run env:install
```

您的GeChiUI网站将在http://localhost:8899。您可以在项目根目录下的`.env` 文件中查看或更改配置。

#### 文件监控程序

如果您正在对GeChiUI核心文件进行更改，则应启动文件监控程序，以便根据需要生成或复制文件：

```
npm run dev
```

停止观察, 在终端中使用组合键 `ctrl+c`.

#### 运行 [GC-CLI](https://make.gechiui.com/cli/handbook/) 命令

```
npm run env:cli <command>
```

GC-CLI有很多[有用的命令](https://developer.gechiui.com/cli/commands/) 你可以在GeChiUI网站上使用。如果文档中提到运行`gc`，请改为运行`npm run env:cli`。例如：

```
npm run env:cli help
```

#### 运行测试

这些命令分别运行PHP和端到端测试套件：

```
npm run test:php
npm run test:e2e
```

#### 重新启动开发环境

如果在`docker-compose.yml` or `.env`中更改了配置，则可能需要重新启动环境。使用以下命令重新启动环境：

```
npm run env:restart
```

#### 停止开发环境

当您不在使用开发环境，可以停止环境：

```
npm run env:stop
```

#### 重新启动开发环境

再次启动环境只需一个命令：

```
npm run env:start
```

## 超管密码

以下是默认的环境超管密码：

* 数据库名称: `gechiui_develop`
* 用户名: `root`
* 密码: `password`

管理员后台地址： http://localhost:8899/gc-admin.

* 管理员用户名: `admin`
* 管理员密码: `password`

要生成新密码（推荐）：


1. 登录后台仪表板
2. 点击左侧的用户菜单
3. 点击管理员用户下方的编辑链接
4. 向下滚动并单击“生成密码”。使用此密码（推荐）或更改密码，然后单击“更新用户”。如果使用生成的密码，请确保将其保存在某个位置（密码管理器等）。

#### 发布生产环境

需要根据实际情况修改文件`Gruntfile.js`中的语句`dev: webpackConfig( { environment: 'production', buildTarget: WORKING_DIR } ),` environment属性值,并运行以下命令：

```
npm run build
```
将会多出一个`build`文件夹，用于上传到生产环境中。

