<?php
// ===== 递归生成节点和边（优化垂直间距） =====
function buildNode($data, $parentId = null, $level = 0, $relX = 0, $relY = 0, &$nodes, &$edges, &$nodeIdCounter) {
    $id = 'node_' . ($nodeIdCounter++);
    $node = [
        'id' => $id,
        'title' => $data['title'],
        'relX' => $relX,
        'relY' => $relY,
        'level' => $level
    ];
    $nodes[] = $node;
    if ($parentId !== null) {
        $edges[] = ['from' => $parentId, 'to' => $id];
    }
    if (isset($data['children']) && is_array($data['children'])) {
        $childCount = count($data['children']);
        $baseX = $relX + 130; // 水平间距不变
        // 垂直间距压缩：原35→20，70→40
        $startY = $relY - ($childCount - 1) * 20;
        foreach ($data['children'] as $index => $child) {
            $childY = $startY + $index * 40;
            buildNode($child, $id, $level + 1, $baseX, $childY, $nodes, $edges, $nodeIdCounter);
        }
    }
}

// ===== 默认数据（完整版）=====
$defaultMindData = [
    'title' => '2026 AI搞钱真实路线图：从0到月入1万',
    'children' => [
        [
            'title' => '一、核心认知（破局）',
            'children' => [
                ['title' => '残酷真相1：90%的人在“假搞钱”（无产出=无收入）'],
                ['title' => '残酷真相2：赚钱靠“信息差+执行力+复利”，不是蛮力'],
                ['title' => '残酷真相3：先苦后甜（100篇铺垫，1篇爆发）'],
                ['title' => '残酷真相4：先完成再完美（执行＞策划）'],
                ['title' => '残酷真相5：长期主义（6-12个月沉淀期）']
            ]
        ],
        [
            'title' => '二、普通人最易赚第一桶金的5条路（低门槛·2026适配）',
            'children' => [
                [
                    'title' => '路径1：AI提示词变现',
                    'children' => [
                        ['title' => '产品：行业专属提示词包（电商、文案、设计）'],
                        ['title' => '渠道：小红书图文、闲鱼、知识星球'],
                        ['title' => '门槛：懂细分行业，会整理']
                    ]
                ],
                [
                    'title' => '路径2：AI内容接单',
                    'children' => [
                        ['title' => '业务：短视频脚本、公众号文章、PPT排版'],
                        ['title' => '渠道：猪八戒、朋友圈、社群'],
                        ['title' => '门槛：会用AI工具，有审校能力']
                    ]
                ],
                [
                    'title' => '路径3：AI工具代运营/定制',
                    'children' => [
                        ['title' => '业务：帮商家做AI自动化客服、内容生成工具'],
                        ['title' => '渠道：抖音同城、企业微信'],
                        ['title' => '门槛：基础编程（HTML/PHP）或工具整合能力']
                    ]
                ],
                [
                    'title' => '路径4：AI教程卖课（小课）',
                    'children' => [
                        ['title' => '产品：9.9-49元短课（如“AI做悬浮计时器”）'],
                        ['title' => '渠道：小红书引流→私域成交'],
                        ['title' => '门槛：会做步骤拆解，能出案例']
                    ]
                ],
                [
                    'title' => '路径5：AI图文带货',
                    'children' => [
                        ['title' => '产品：AI生成素材包、模板、插件'],
                        ['title' => '渠道：小红书、抖音橱窗'],
                        ['title' => '门槛：会选品，会做封面图']
                    ]
                ]
            ]
        ],
        [
            'title' => '三、2026最稳的AI副业模型（可复制）',
            'children' => [
                ['title' => '模型名称：“内容+工具+私域”闭环'],
                ['title' => '第一步：免费输出（AI干货/工具教程）→ 涨粉'],
                ['title' => '第二步：低价产品（提示词包/模板）→ 破冰成交'],
                ['title' => '第三步：高价产品（定制工具/训练营）→ 盈利'],
                ['title' => '核心：用“工具”建立信任，用“服务”提升客单价']
            ]
        ],
        [
            'title' => '四、0粉丝起号流量打法（实操）',
            'children' => [
                [
                    'title' => '平台选择（选1个深耕）',
                    'children' => [
                        ['title' => '小红书：图文（教程/清单）→ 适合卖课/素材'],
                        ['title' => '抖音：口播（真相/避坑）→ 适合引流私域']
                    ]
                ],
                [
                    'title' => '内容选题公式（万能）',
                    'children' => [
                        ['title' => '痛点型：《别再用XX了，2026用AI省10小时》'],
                        ['title' => '清单型：《5个免费AI工具，搞钱必备》'],
                        ['title' => '教程型：《3分钟，用AI做一个悬浮记事本》']
                    ]
                ],
                [
                    'title' => '发布节奏（死磕100天）',
                    'children' => [
                        ['title' => '频率：每天1条（雷打不动）'],
                        ['title' => '时间：小红书早8晚8，抖音晚7-9'],
                        ['title' => '复盘：只看“播放量/收藏率”，不看点赞']
                    ]
                ]
            ]
        ],
        [
            'title' => '五、从0到月入1万执行路径（分阶段）',
            'children' => [
                [
                    'title' => '第1-7天：定位与准备',
                    'children' => [
                        ['title' => '确定细分领域（如“AI工具制作”“电商AI文案”）'],
                        ['title' => '准备账号：头像、简介（带关键词）、背景图'],
                        ['title' => '整理10个选题，用AI生成初稿']
                    ]
                ],
                [
                    'title' => '第8-30天：冷启动（破0）',
                    'children' => [
                        ['title' => '每天发布1条内容，不删不改'],
                        ['title' => '评论区互动，私信同行学习'],
                        ['title' => '推出9.9元低价产品（如提示词包），练成交']
                    ]
                ],
                [
                    'title' => '第31-60天：放大（涨粉+变现）',
                    'children' => [
                        ['title' => '优化高播放量选题，批量生产同类型内容'],
                        ['title' => '引流私域（公众号/企业微信），发干货资料'],
                        ['title' => '月目标：变现1000元（验证模型）']
                    ]
                ],
                [
                    'title' => '第61-100天：稳定（月入1万）',
                    'children' => [
                        ['title' => '迭代产品（推出49-99元小课）'],
                        ['title' => '对接合作（接单/分销）'],
                        ['title' => '月目标：变现1万+（流量复利显现）']
                    ]
                ]
            ]
        ],
        [
            'title' => '六、避坑指南（关键）',
            'children' => [
                ['title' => '坑1：贪多求全（同时做多个平台/多个项目）→ 专注1个'],
                ['title' => '坑2：追求完美（文案改3天，最后不发）→ 先发布再优化'],
                ['title' => '坑3：只学不做（收藏100个教程，不执行1个）→ 每天只做“可变现动作”'],
                ['title' => '坑4：跟风换项目（3天没结果就放弃）→ 死磕6个月']
            ]
        ]
    ]
];

// ===== 如果收到POST请求，解析Markdown并返回JSON =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['markdown'])) {
    $markdown = $_POST['markdown'];
    function parseMarkdownToTree($md) {
        $lines = explode("\n", $md);
        $root = null;
        $stack = [];
        foreach ($lines as $line) {
            $line = rtrim($line);
            if ($line === '') continue;
            if (preg_match('/^(#+)\s+(.+)$/', $line, $matches)) {
                $level = strlen($matches[1]);
                $title = $matches[2];
                $node = ['title' => $title, 'children' => []];
                if ($level == 1) {
                    $root = $node;
                    $stack = [&$root];
                } else {
                    if (isset($stack[$level-2])) {
                        $parent = &$stack[$level-2];
                        $parent['children'][] = $node;
                        while (count($stack) > $level-1) array_pop($stack);
                        $stack[] = &$node;
                    }
                }
            }
            elseif (preg_match('/^(\s*)\-\s+(.+)$/', $line, $matches)) {
                $indent = strlen($matches[1]);
                $title = $matches[2];
                $node = ['title' => $title, 'children' => []];
                $level = floor($indent / 2) + 1;
                if (empty($stack)) {
                    $root['children'][] = $node;
                    $stack[] = &$node;
                } else {
                    while (count($stack) > $level) array_pop($stack);
                    if (count($stack) == 0) {
                        $root['children'][] = $node;
                        $stack[] = &$node;
                    } else {
                        $parent = &$stack[count($stack)-1];
                        $parent['children'][] = $node;
                        $stack[] = &$node;
                    }
                }
            }
        }
        return $root;
    }
    
    $newMindData = parseMarkdownToTree($markdown);
    if ($newMindData === null) {
        http_response_code(400);
        echo json_encode(['error' => '解析失败']);
        exit;
    }
    
    $nodes = [];
    $edges = [];
    $nodeIdCounter = 0;
    buildNode($newMindData, null, 0, 0, 0, $nodes, $edges, $nodeIdCounter);
    
    header('Content-Type: application/json');
    echo json_encode(['nodes' => $nodes, 'edges' => $edges]);
    exit;
}

// ===== 默认生成节点 =====
$nodes = [];
$edges = [];
$nodeIdCounter = 0;
buildNode($defaultMindData, null, 0, 0, 0, $nodes, $edges, $nodeIdCounter);
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>动态思维导图 · 可调速</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; }
        body {
            background:#000; color:#fff; font-family:Arial, sans-serif;
            height:100%; display:flex; flex-direction:column; overflow:hidden;
        }
        #canvas-container {
            flex:1; position:relative; background:#1a1a1a; touch-action:none;
            min-height:0;
        }
        canvas {
            display:block; width:100%; height:100%; background:transparent;
        }
        .control-panel {
            background:#222; padding:8px 10px; display:flex; flex-direction:column; gap:6px;
            border-top:3px solid #07c160; flex-shrink:0;
        }
        .button-row {
            display:flex; justify-content:space-around; gap:6px;
        }
        .speed-row {
            display:flex; align-items:center; gap:8px; background:#333;
            padding:6px 10px; border-radius:8px; border:1px solid #07c160;
        }
        .speed-row label {
            color:#0ff; font-size:14px; white-space:nowrap;
        }
        .speed-row span {
            color:#ff0; font-weight:bold; min-width:35px; text-align:center;
        }
        input[type=range] {
            flex:1; height:6px; -webkit-appearance:none; background:#07c160;
            border-radius:3px; outline:none;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance:none; width:18px; height:18px; border-radius:50%;
            background:#fff; border:2px solid #0a0; cursor:pointer;
        }
        button {
            background:#07c160; color:white; font-size:16px; font-weight:bold;
            border:2px solid #0a0; border-radius:10px; padding:10px 0;
            flex:1; cursor:pointer; box-shadow:0 4px 8px rgba(0,255,0,0.5);
            transition:0.2s; min-width:80px;
        }
        button:active { background:#059e4b; transform:scale(0.98); }
        button.speaking { background:#ff5722; border-color:#f50; box-shadow:0 4px 8px #f50; }
        textarea {
            width:100%; height:90px; background:#333; color:#0ff;
            border:1px solid #07c160; border-radius:5px; padding:6px;
            font-family:monospace; font-size:13px; resize:vertical;
        }
        #subtitle {
            height:36px; background:#222; color:#0ff; font-size:16px;
            line-height:36px; text-align:center; border-top:1px solid #07c160;
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis; padding:0 8px;
            flex-shrink:0;
        }
        #debug {
            position:fixed; top:5px; left:5px; color:#0f0; font-size:12px;
            background:rgba(0,0,0,0.7); padding:2px 5px; border-radius:3px; z-index:999;
        }
        .load-btn { background:#2196f3; border-color:#1976d2; }
    </style>
</head>
<body>
    <div id="debug">初始化中...</div>
    <div id="canvas-container"><canvas id="mindCanvas"></canvas></div>
    <div id="subtitle"></div>
    <div class="control-panel">
        <textarea id="mdInput" placeholder="在此粘贴Markdown格式的思维导图..."># 2026 AI搞钱真实路线图：从0到月入1万
## 一、核心认知（破局）
- 残酷真相1：90%的人在“假搞钱”（无产出=无收入）
- 残酷真相2：赚钱靠“信息差+执行力+复利”，不是蛮力
- 残酷真相3：先苦后甜（100篇铺垫，1篇爆发）
- 残酷真相4：先完成再完美（执行＞策划）
- 残酷真相5：长期主义（6-12个月沉淀期）
## 二、普通人最易赚第一桶金的5条路（低门槛·2026适配）
- 路径1：AI提示词变现
  - 产品：行业专属提示词包（电商、文案、设计）
  - 渠道：小红书图文、闲鱼、知识星球
  - 门槛：懂细分行业，会整理
- 路径2：AI内容接单
  - 业务：短视频脚本、公众号文章、PPT排版
  - 渠道：猪八戒、朋友圈、社群
  - 门槛：会用AI工具，有审校能力
- 路径3：AI工具代运营/定制
  - 业务：帮商家做AI自动化客服、内容生成工具
  - 渠道：抖音同城、企业微信
  - 门槛：基础编程（HTML/PHP）或工具整合能力
- 路径4：AI教程卖课（小课）
  - 产品：9.9-49元短课（如“AI做悬浮计时器”）
  - 渠道：小红书引流→私域成交
  - 门槛：会做步骤拆解，能出案例
- 路径5：AI图文带货
  - 产品：AI生成素材包、模板、插件
  - 渠道：小红书、抖音橱窗
  - 门槛：会选品，会做封面图
## 三、2026最稳的AI副业模型（可复制）
- 模型名称：“内容+工具+私域”闭环
- 第一步：免费输出（AI干货/工具教程）→ 涨粉
- 第二步：低价产品（提示词包/模板）→ 破冰成交
- 第三步：高价产品（定制工具/训练营）→ 盈利
- 核心：用“工具”建立信任，用“服务”提升客单价
## 四、0粉丝起号流量打法（实操）
- 平台选择（选1个深耕）
  - 小红书：图文（教程/清单）→ 适合卖课/素材
  - 抖音：口播（真相/避坑）→ 适合引流私域
- 内容选题公式（万能）
  - 痛点型：《别再用XX了，2026用AI省10小时》
  - 清单型：《5个免费AI工具，搞钱必备》
  - 教程型：《3分钟，用AI做一个悬浮记事本》
- 发布节奏（死磕100天）
  - 频率：每天1条（雷打不动）
  - 时间：小红书早8晚8，抖音晚7-9
  - 复盘：只看“播放量/收藏率”，不看点赞
## 五、从0到月入1万执行路径（分阶段）
- 第1-7天：定位与准备
  - 确定细分领域（如“AI工具制作”“电商AI文案”）
  - 准备账号：头像、简介（带关键词）、背景图
  - 整理10个选题，用AI生成初稿
- 第8-30天：冷启动（破0）
  - 每天发布1条内容，不删不改
  - 评论区互动，私信同行学习
  - 推出9.9元低价产品（如提示词包），练成交
- 第31-60天：放大（涨粉+变现）
  - 优化高播放量选题，批量生产同类型内容
  - 引流私域（公众号/企业微信），发干货资料
  - 月目标：变现1000元（验证模型）
- 第61-100天：稳定（月入1万）
  - 迭代产品（推出49-99元小课）
  - 对接合作（接单/分销）
  - 月目标：变现1万+（流量复利显现）
## 六、避坑指南（关键）
- 坑1：贪多求全（同时做多个平台/多个项目）→ 专注1个
- 坑2：追求完美（文案改3天，最后不发）→ 先发布再优化
- 坑3：只学不做（收藏100个教程，不执行1个）→ 每天只做“可变现动作”
- 坑4：跟风换项目（3天没结果就放弃）→ 死磕6个月</textarea>
        <div class="button-row">
            <button id="loadMdBtn" class="load-btn">📥 加载</button>
            <button id="resetBtn">🔄 重置</button>
            <button id="speechBtn">▶ 播放</button>
        </div>
        <!-- 新增语速调节滑块 -->
        <div class="speed-row">
            <label for="speedRange">语速</label>
            <span id="speedValue">0.9</span>
            <input type="range" id="speedRange" min="0.5" max="2" step="0.1" value="0.9">
        </div>
    </div>

    <script>
        // ---------- 数据初始化 ----------
        let nodesData = <?php echo json_encode($nodes, JSON_UNESCAPED_UNICODE); ?>;
        let edgesData = <?php echo json_encode($edges, JSON_UNESCAPED_UNICODE); ?>;
        let nodeMap = {};
        let nodes = [];

        function rebuildFromData(nodesArr, edgesArr) {
            nodeMap = {};
            nodesArr.forEach(n => nodeMap[n.id] = { ...n, children: [] });
            edgesArr.forEach(e => {
                if (nodeMap[e.from] && nodeMap[e.to]) {
                    nodeMap[e.from].children.push(e.to);
                }
            });
            nodes = Object.values(nodeMap);
        }
        rebuildFromData(nodesData, edgesData);

        // ---------- 全局变量 ----------
        const canvas = document.getElementById('mindCanvas');
        const ctx = canvas.getContext('2d');
        const subtitleDiv = document.getElementById('subtitle');
        const debugDiv = document.getElementById('debug');
        let offsetX = 0, offsetY = 0;
        let lastPointer = null;
        let isSpeaking = false;
        let speechQueue = [];
        let speechIndex = 0;
        let speechTimer = null;
        let highlightedNodeId = null;
        let visibleNodes = new Set();
        let growingNodeId = null;
        let growProgress = 0;
        let growAnimationFrame = null;
        const GROW_DURATION = 300;
        let canvasWidth = 0, canvasHeight = 0;
        let scaleFactor = 1.0;
        let animFrame = null;
        let baseX = 0, baseY = 0;
        // 新增语速变量
        let speechRate = 0.9;

        // 语速滑块绑定
        const speedRange = document.getElementById('speedRange');
        const speedSpan = document.getElementById('speedValue');
        speedRange.addEventListener('input', (e) => {
            speechRate = parseFloat(e.target.value);
            speedSpan.textContent = speechRate.toFixed(1);
        });

        function updateDebug() {
            debugDiv.innerText = `可见: ${visibleNodes.size}/${nodes.length} | 画布: ${canvasWidth.toFixed(0)}x${canvasHeight.toFixed(0)} | 偏移: (${offsetX.toFixed(0)},${offsetY.toFixed(0)}) | 语速: ${speechRate.toFixed(1)}`;
        }

        // 计算节点实际坐标（根节点靠左，留出向右生长空间）
        function computeNodePositions() {
            baseX = canvasWidth * 0.1;
            baseY = canvasHeight * 0.2;
            nodes.forEach(node => {
                node.x = baseX + node.relX;
                node.y = baseY + node.relY;
            });
        }

        // 居中所有节点
        function centerView() {
            if (nodes.length === 0 || canvasWidth === 0) return;
            computeNodePositions();
            let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
            nodes.forEach(n => {
                minX = Math.min(minX, n.x);
                maxX = Math.max(maxX, n.x);
                minY = Math.min(minY, n.y);
                maxY = Math.max(maxY, n.y);
            });
            const centerX = (minX + maxX) / 2;
            const centerY = (minY + maxY) / 2;
            offsetX = canvasWidth / 2 - centerX;
            offsetY = canvasHeight / 2 - centerY;
            render();
        }

        function resizeCanvas() {
            const container = document.getElementById('canvas-container');
            canvasWidth = container.clientWidth;
            canvasHeight = container.clientHeight;
            if (canvasWidth === 0 || canvasHeight === 0) {
                setTimeout(resizeCanvas, 100);
                return;
            }
            canvas.width = canvasWidth * devicePixelRatio;
            canvas.height = canvasHeight * devicePixelRatio;
            canvas.style.width = canvasWidth + 'px';
            canvas.style.height = canvasHeight + 'px';
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(devicePixelRatio, devicePixelRatio);
            centerView();
        }

        function render() {
            if (!ctx || canvasWidth === 0) return;
            ctx.clearRect(0, 0, canvasWidth, canvasHeight);

            // 绘制连线
            ctx.beginPath();
            ctx.strokeStyle = '#888';
            ctx.lineWidth = 2;
            edgesData.forEach(edge => {
                if (visibleNodes.has(edge.from) && visibleNodes.has(edge.to)) {
                    const fromNode = nodeMap[edge.from];
                    const toNode = nodeMap[edge.to];
                    ctx.moveTo(fromNode.x + offsetX, fromNode.y + offsetY);
                    ctx.lineTo(toNode.x + offsetX, toNode.y + offsetY);
                }
            });
            ctx.stroke();

            // 绘制节点
            nodes.forEach(node => {
                if (!visibleNodes.has(node.id)) return;

                const x = node.x + offsetX;
                const y = node.y + offsetY;

                let radius = 28;
                if (node.id === growingNodeId) {
                    radius = 28 * growProgress;
                    if (radius < 2) radius = 2;
                }

                let fillColor, shadowColor, shadowBlur;
                if (node.id === highlightedNodeId) {
                    if (node.id !== growingNodeId) {
                        radius = 28 * (1 + (scaleFactor - 1) * 0.5);
                    }
                    fillColor = '#ffaa00';
                    shadowColor = '#ffaa00';
                    shadowBlur = 25;
                } else {
                    fillColor = '#07c160';
                    shadowBlur = 0;
                }

                ctx.shadowColor = shadowColor || 'transparent';
                ctx.shadowBlur = shadowBlur || 0;
                ctx.fillStyle = fillColor;

                ctx.beginPath();
                ctx.arc(x, y, radius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 2;
                ctx.stroke();

                if (radius > 10) {
                    ctx.fillStyle = '#fff';
                    ctx.font = '12px Arial';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    drawWrappedText(node.title, x, y, 50, 14);
                }
            });

            ctx.shadowBlur = 0;
            updateDebug();
        }

        function drawWrappedText(text, x, y, maxWidth, lineHeight) {
            const chars = text.split('');
            let line = '';
            let lines = [];
            for (let char of chars) {
                const testLine = line + char;
                const metrics = ctx.measureText(testLine);
                if (metrics.width > maxWidth && line.length > 0) {
                    lines.push(line);
                    line = char;
                } else {
                    line = testLine;
                }
            }
            if (line) lines.push(line);
            if (lines.length > 2) {
                lines = [lines[0], lines[1].slice(0, -2) + '…'];
            }
            for (let i = 0; i < lines.length; i++) {
                ctx.fillText(lines[i], x, y - (lines.length-1)*lineHeight/2 + i*lineHeight);
            }
        }

        function animateScale() {
            if (!highlightedNodeId) return;
            scaleFactor = 1.15 + 0.15 * Math.sin(Date.now() / 200);
            render();
            animFrame = requestAnimationFrame(animateScale);
        }

        function startGrowAnimation(nodeId, onComplete) {
            if (growAnimationFrame) cancelAnimationFrame(growAnimationFrame);
            if (animFrame) cancelAnimationFrame(animFrame);
            growingNodeId = nodeId;
            growProgress = 0;
            const startTime = performance.now();
            function step(now) {
                const elapsed = now - startTime;
                growProgress = Math.min(elapsed / GROW_DURATION, 1);
                render();
                if (growProgress < 1) {
                    growAnimationFrame = requestAnimationFrame(step);
                } else {
                    growingNodeId = null;
                    render();
                    if (onComplete) onComplete();
                }
            }
            growAnimationFrame = requestAnimationFrame(step);
        }

        function stopAllAnimations() {
            if (growAnimationFrame) cancelAnimationFrame(growAnimationFrame);
            if (animFrame) cancelAnimationFrame(animFrame);
            growingNodeId = null;
            highlightedNodeId = null;
            scaleFactor = 1.0;
        }

        function stopSpeech() {
            if (speechTimer) clearTimeout(speechTimer);
            if (window.speechSynthesis) window.speechSynthesis.cancel();
            subtitleDiv.innerText = '';
            isSpeaking = false;
            stopAllAnimations();
            render();
            const speechBtn = document.getElementById('speechBtn');
            speechBtn.classList.remove('speaking');
            speechBtn.innerText = '▶ 播放';
        }

        function buildBFSQueue() {
            const queue = [];
            const root = nodes.find(n => n.level === 0);
            if (!root) return [];

            const visited = new Set();
            const bfs = [root];
            while (bfs.length > 0) {
                const node = bfs.shift();
                if (visited.has(node.id)) continue;
                visited.add(node.id);
                queue.push(node);
                if (node.children && node.children.length) {
                    for (let childId of node.children) {
                        const childNode = nodeMap[childId];
                        if (childNode) bfs.push(childNode);
                    }
                }
            }
            return queue;
        }

        function playNext() {
            if (!isSpeaking) return;
            if (speechIndex >= speechQueue.length) {
                stopSpeech();
                return;
            }

            const currentNode = speechQueue[speechIndex];
            visibleNodes.add(currentNode.id);
            
            stopAllAnimations();
            startGrowAnimation(currentNode.id, () => {
                highlightedNodeId = currentNode.id;
                subtitleDiv.innerText = currentNode.title;
                if (animFrame) cancelAnimationFrame(animFrame);
                animateScale();

                const utterance = new SpeechSynthesisUtterance(currentNode.title);
                utterance.lang = 'zh-CN';
                utterance.rate = speechRate;  // 使用当前语速
                utterance.onend = () => {
                    highlightedNodeId = null;
                    stopAllAnimations();
                    speechIndex++;
                    speechTimer = setTimeout(playNext, 400);
                };
                utterance.onerror = () => {
                    highlightedNodeId = null;
                    stopAllAnimations();
                    speechIndex++;
                    speechTimer = setTimeout(playNext, 400);
                };
                window.speechSynthesis.speak(utterance);
            });
        }

        function startSpeech() {
            if (!window.speechSynthesis) {
                alert('您的浏览器不支持语音合成');
                return;
            }
            if (isSpeaking) {
                stopSpeech();
                return;
            }
            if (nodes.length === 0) return;

            visibleNodes.clear();
            speechQueue = buildBFSQueue();
            speechIndex = 0;
            isSpeaking = true;

            const speechBtn = document.getElementById('speechBtn');
            speechBtn.classList.add('speaking');
            speechBtn.innerText = '⏹ 停止';

            playNext();
        }

        function handlePointerStart(e) {
            e.preventDefault();
            const rect = canvas.getBoundingClientRect();
            const canvasX = e.clientX - rect.left;
            const canvasY = e.clientY - rect.top;

            lastPointer = { x: canvasX, y: canvasY };

            for (let i = nodes.length - 1; i >= 0; i--) {
                const node = nodes[i];
                if (!visibleNodes.has(node.id)) continue;
                const dx = canvasX - (node.x + offsetX);
                const dy = canvasY - (node.y + offsetY);
                if (Math.sqrt(dx*dx + dy*dy) < 30) {
                    if (isSpeaking) stopSpeech();
                    subtitleDiv.innerText = node.title;
                    const utterance = new SpeechSynthesisUtterance(node.title);
                    utterance.lang = 'zh-CN';
                    utterance.rate = speechRate;  // 使用当前语速
                    window.speechSynthesis.speak(utterance);
                    break;
                }
            }
        }

        function handlePointerMove(e) {
            e.preventDefault();
            if (!lastPointer) return;
            const rect = canvas.getBoundingClientRect();
            const canvasX = e.clientX - rect.left;
            const canvasY = e.clientY - rect.top;

            const dx = canvasX - lastPointer.x;
            const dy = canvasY - lastPointer.y;

            offsetX += dx;
            offsetY += dy;
            lastPointer = { x: canvasX, y: canvasY };
            render();
        }

        function handlePointerEnd(e) {
            e.preventDefault();
            lastPointer = null;
        }

        function resetCanvas() {
            stopSpeech();
            visibleNodes = new Set(nodes.map(n => n.id));
            offsetX = 0;
            offsetY = 0;
            centerView();
        }

        // 加载Markdown
        document.getElementById('loadMdBtn').addEventListener('click', async () => {
            const mdText = document.getElementById('mdInput').value;
            if (!mdText.trim()) return;

            debugDiv.innerText = '正在加载Markdown...';

            const formData = new FormData();
            formData.append('markdown', mdText);

            try {
                const response = await fetch(window.location.href, { method: 'POST', body: formData });
                if (!response.ok) throw new Error('服务器错误');
                const data = await response.json();
                if (data.error) throw new Error(data.error);

                nodesData = data.nodes;
                edgesData = data.edges;
                rebuildFromData(nodesData, edgesData);
                resetCanvas();
                debugDiv.innerText = 'Markdown加载成功';
            } catch (err) {
                debugDiv.innerText = '加载失败: ' + err.message;
            }
        });

        // 事件绑定
        canvas.addEventListener('pointerdown', handlePointerStart);
        canvas.addEventListener('pointermove', handlePointerMove);
        canvas.addEventListener('pointerup', handlePointerEnd);
        canvas.addEventListener('pointercancel', handlePointerEnd);
        canvas.addEventListener('touchstart', (e) => e.preventDefault(), { passive: false });

        document.getElementById('resetBtn').addEventListener('click', resetCanvas);
        document.getElementById('speechBtn').addEventListener('click', startSpeech);

        // 初始化
        window.addEventListener('load', () => {
            resizeCanvas();
            resetCanvas();
            debugDiv.innerText = '就绪，可粘贴Markdown';
        });

        window.addEventListener('resize', () => {
            resizeCanvas();
        });
    </script>
</body>
</html>



