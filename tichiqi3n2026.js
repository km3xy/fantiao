"ui";

// ==================== 权限检查 ====================
if (!floaty.checkPermission()) {
    dialogs.build({
        title: "需要悬浮窗权限",
        content: "请在设置中允许本应用显示悬浮窗",
        positive: "去设置",
        negative: "取消"
    }).on("positive", () => {
        floaty.requestPermission();
    }).show();
    exit();
}

// ==================== 默认提词内容 ====================
const DEFAULT_TEXT = `
大家好，欢迎来到我的频道。

今天分享一个
AutoX 悬浮提词器。

透明背景
录视频非常好用。

如果觉得不错
记得点赞关注。
`.trim();

// ==================== 主界面 ====================
ui.layout(
    <vertical padding="16">
        <input id="text" hint="输入提词内容" h="150"/>
        <horizontal>
            <button id="copy" text="复制" style="Widget.AppCompat.Button.Borderless"/>
            <button id="clear" text="清空" style="Widget.AppCompat.Button.Borderless"/>
            <button id="start" text="启动提词器" style="Widget.AppCompat.Button.Colored" layout_weight="1"/>
        </horizontal>
    </vertical>
);

let storage = storages.create("teleprompter");

let lastText = storage.get("lastText");
if (lastText && lastText.trim() !== "") {
    ui.text.setText(lastText);
} else {
    ui.text.setText(DEFAULT_TEXT);
}

// ==================== 全局变量 ====================
let floatWindow = null;
let scrollThread = null;
let isRunning = true;
let speed = 1.0;

// ==================== 复制按钮点击（主界面）====================
ui.copy.click(() => {
    let content = ui.text.text();
    if (content) {
        setClip(content);
        toast("已复制到剪贴板");
    } else {
        toast("没有内容可复制");
    }
});

// ==================== 清空按钮点击 ====================
ui.clear.click(() => {
    ui.text.setText("");
});

// ==================== 启动按钮点击 ====================
ui.start.click(() => {
    let content = ui.text.text().trim();
    if (!content) {
        toast("请输入提词内容");
        return;
    }
    storage.put("lastText", content);
    startFloat(content);
});

// ==================== 启动悬浮窗 ====================
function startFloat(text) {
    if (floatWindow) stopFloat();

    // 创建悬浮窗（增加 copyBtn）
    floatWindow = floaty.rawWindow(
        <frame id="bg" bg="#aa000000" padding="8" gravity="center">
            <vertical>
                <text id="content"
                    text={text}
                    textColor="#ffffff"
                    textSize="22sp"
                    shadowRadius="2"
                    shadowDx="1"
                    shadowDy="1"
                    shadowColor="#99000000"
                    padding="8 16"
                    gravity="center"/>
                <horizontal gravity="center" marginTop="8">
                    <button id="pauseBtn" text="⏸" style="Widget.AppCompat.Button.Borderless"/>
                    <SeekBar id="speedSeek" progress="50" max="200" layout_weight="1"/>
                    <text id="speedText" text="1.0x" textColor="#ffffff" layout_weight="0"/>
                    <button id="copyBtn" text="📋" style="Widget.AppCompat.Button.Borderless"/>
                    <button id="editBtn" text="✏️" style="Widget.AppCompat.Button.Borderless"/>
                    <button id="closeBtn" text="❌" style="Widget.AppCompat.Button.Borderless"/>
                </horizontal>
            </vertical>
        </frame>
    );

    if (!floatWindow || typeof floatWindow.setSize !== 'function') {
        toast("悬浮窗创建失败，请重试");
        floatWindow = null;
        return;
    }

    floatWindow.setSize(device.width - 32, -2);
    floatWindow.setPosition(16, device.height * 0.7);
    floatWindow.content.scrollTo(0, 0);

    // ========== 事件绑定 ==========
    floatWindow.pauseBtn.click(() => {
        isRunning = !isRunning;
        ui.run(() => {
            if (floatWindow && floatWindow.pauseBtn) {
                floatWindow.pauseBtn.setText(isRunning ? "⏸" : "▶️");
            }
        });
    });

    floatWindow.speedSeek.setOnSeekBarChangeListener({
        onProgressChanged: (seekBar, progress, fromUser) => {
            speed = progress / 50;
            if (speed < 0.5) speed = 0.5;
            ui.run(() => {
                if (floatWindow && floatWindow.speedText) {
                    floatWindow.speedText.setText(speed.toFixed(1) + "x");
                }
            });
        }
    });

    // 新增：复制按钮点击事件
    floatWindow.copyBtn.click(() => {
        let content = floatWindow.content.getText();
        if (content) {
            setClip(content);
            toast("已复制到剪贴板");
        } else {
            toast("没有内容可复制");
        }
    });

    floatWindow.editBtn.click(() => {
        dialogs.rawInput("修改提词内容", floatWindow.content.getText())
            .then(newText => {
                if (newText) {
                    ui.run(() => {
                        if (floatWindow && floatWindow.content) {
                            floatWindow.content.setText(newText);
                            floatWindow.content.scrollTo(0, 0);
                        }
                    });
                }
            });
    });

    floatWindow.closeBtn.click(() => stopFloat());

    // ========== 拖动与边缘吸附 ==========
    let touchX, touchY, windowX, windowY;
    floatWindow.bg.setOnTouchListener((view, event) => {
        switch (event.getAction()) {
            case event.ACTION_DOWN:
                isRunning = false;
                touchX = event.getRawX();
                touchY = event.getRawY();
                windowX = floatWindow.getX();
                windowY = floatWindow.getY();
                return true;
            case event.ACTION_MOVE:
                let dx = event.getRawX() - touchX;
                let dy = event.getRawY() - touchY;
                floatWindow.setPosition(windowX + dx, windowY + dy);
                return true;
            case event.ACTION_UP:
            case event.ACTION_CANCEL:
                isRunning = true;
                let x = floatWindow.getX();
                let y = floatWindow.getY();
                let newX = x < device.width / 2 ? 0 : device.width - floatWindow.getWidth();
                floatWindow.setPosition(newX, y);
                return true;
        }
        return false;
    });

    // ========== 启动滚动线程 ==========
    scrollThread = threads.start(function() {
        while (scrollThread && !scrollThread.isInterrupted() && floatWindow) {
            if (isRunning) {
                ui.run(() => {
                    if (floatWindow && floatWindow.content) {
                        floatWindow.content.scrollBy(0, speed);
                        if (floatWindow.content.getScrollY() >= 
                            floatWindow.content.getHeight() - floatWindow.content.getMeasuredHeight()) {
                            floatWindow.content.scrollTo(0, 0);
                        }
                    }
                });
            }
            sleep(30);
        }
    });
}

// ==================== 关闭悬浮窗 ====================
function stopFloat() {
    if (scrollThread) {
        scrollThread.interrupt();
        scrollThread = null;
    }
    if (floatWindow) {
        floatWindow.close();
        floatWindow = null;
    }
    isRunning = true;
    speed = 1.0;
}

ui.emitter.on("activity_destroy", () => stopFloat());





