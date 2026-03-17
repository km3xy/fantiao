"ui";

// ------------------------ 悬浮窗UI（增加关闭按钮）-----------------------
var win = floaty.window(
    <frame id="root" bg="#16222ee0" w="220" h="160" 
        paddingLeft="16" paddingTop="16" paddingRight="16" paddingBottom="12" >
        <vertical>
            <horizontal gravity="right" marginTop="-8" marginRight="-8">
                <button id="closeBtn" text="×" w="24" h="24" textSize="16sp"
                    bg="#33ff0000" textColor="#ffffff" radius="12"/>
            </horizontal>
            <vertical gravity="center" marginTop="-8">
                <horizontal gravity="center">
                    <text id="timeValue" text="0.0" textSize="48sp" textColor="#ffffff" 
                        textStyle="bold" shadowRadius="8" shadowColor="#00a0ff" 
                        shadowDx="0" shadowDy="0" gravity="center" fontFamily="monospace"/>
                    <text text="s" textSize="18sp" textColor="#a0ffffff" 
                        marginLeft="2" gravity="bottom" paddingBottom="6"/>
                </horizontal>
                <horizontal gravity="center" marginTop="8">
                    <button id="startPauseBtn" text="▶" w="56" h="56" textSize="28sp"
                        bg="#28428fff" textColor="#ffffff" radius="28" marginRight="12"/>
                    <button id="resetBtn" text="↻" w="56" h="56" textSize="28sp"
                        bg="#28ffb464" textColor="#ffffff" radius="28"/>
                </horizontal>
                <text id="dragHint" text="⣿ 拖拽" textSize="10sp" textColor="#66ffffff" 
                    gravity="end" marginTop="4"/>
            </vertical>
        </vertical>
    </frame>
);

// 初始位置
win.setPosition(20, 100);

// 获取控件引用
var root = win.root;
var timeValue = win.timeValue;
var startPauseBtn = win.startPauseBtn;
var resetBtn = win.resetBtn;
var closeBtn = win.closeBtn;
var dragHint = win.dragHint;

// ------------------------ 全局状态 ------------------------
var isWindowAlive = true;           // 窗口是否有效
var running = false;                // 计时器是否运行
var startTime = 0;
var pausedElapsedMs = 0;
var timerInterval = null;           // 计时器刷新句柄
var clampInterval = null;           // 边界检测句柄

// ------------------------ 计时器逻辑 ------------------------
function getCurrentElapsedMs() {
    if (running) {
        return (new Date().getTime() - startTime) + pausedElapsedMs;
    } else {
        return pausedElapsedMs;
    }
}

function updateDisplay() {
    var elapsedMs = getCurrentElapsedMs();
    var seconds = (elapsedMs / 1000).toFixed(1);
    ui.run(function() {
        if (timeValue) timeValue.setText(seconds);
    });
}

function updateStartPauseIcon() {
    ui.run(function() {
        if (startPauseBtn) startPauseBtn.setText(running ? '⏸' : '▶');
    });
}

function start() {
    if (!running) {
        pausedElapsedMs = 0;
        startTime = new Date().getTime();
        running = true;
        updateStartPauseIcon();
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(function() {
            if (running && isWindowAlive) updateDisplay();
        }, 100);
        updateDisplay();
    }
}

function pause() {
    if (running) {
        pausedElapsedMs = getCurrentElapsedMs();
        running = false;
        updateStartPauseIcon();
        updateDisplay();
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
    }
}

function reset() {
    pausedElapsedMs = 0;
    if (running) {
        running = false;
        updateStartPauseIcon();
    } else {
        updateStartPauseIcon();
    }
    updateDisplay();
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
}

// ------------------------ 安全退出函数 ------------------------
function safeExit() {
    if (!isWindowAlive) return;
    isWindowAlive = false;
    
    // 清除所有定时器
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
    if (clampInterval) {
        clearInterval(clampInterval);
        clampInterval = null;
    }
    
    // 关闭窗口
    try {
        win.close();
    } catch(e) {}
    
    // 停止脚本引擎
    setTimeout(function() {
        engines.myEngine().forceStop();
    }, 100);
}

// ------------------------ 按钮事件 ------------------------
startPauseBtn.click(function() {
    if (running) pause(); else start();
});

resetBtn.click(function() {
    reset();
});

// 关闭按钮：安全退出
closeBtn.click(function() {
    safeExit();
});

// 长按重置退出（备用）
resetBtn.longClick(function() {
    ui.run(function() {
        timeValue.setText("退出");
        startPauseBtn.setText("×");
    });
    setTimeout(safeExit, 500);
    return true;
});

// ------------------------ 拖拽逻辑（修正颜色转换）-----------------------
var dragging = false;
var downX = 0, downY = 0;
var windowX = 0, windowY = 0;

function getButtonBounds() {
    var bounds = [];
    try {
        var views = [startPauseBtn, resetBtn, closeBtn];
        for (var i = 0; i < views.length; i++) {
            var btn = views[i];
            if (btn) {
                var loc = [0, 0];
                btn.getLocationOnScreen(loc);
                bounds.push({
                    left: loc[0],
                    top: loc[1],
                    right: loc[0] + btn.getWidth(),
                    bottom: loc[1] + btn.getHeight()
                });
            }
        }
    } catch(e) {}
    return bounds;
}

function isPointOnButton(x, y, btnBounds) {
    for (var i = 0; i < btnBounds.length; i++) {
        var b = btnBounds[i];
        if (x >= b.left && x <= b.right && y >= b.top && y <= b.bottom) {
            return true;
        }
    }
    return false;
}

setTimeout(function() {
    try {
        if (root && isWindowAlive) {
            root.setOnTouchListener(new android.view.View.OnTouchListener({
                onTouch: function(v, event) {
                    if (!isWindowAlive) return false;  // 窗口已销毁，忽略触摸
                    
                    var action = event.getAction();
                    var rawX = event.getRawX();
                    var rawY = event.getRawY();
                    
                    var btnBounds = getButtonBounds();
                    
                    switch(action) {
                        case android.view.MotionEvent.ACTION_DOWN:
                            if (isPointOnButton(rawX, rawY, btnBounds)) {
                                return false;
                            }
                            downX = rawX;
                            downY = rawY;
                            windowX = win.getX();
                            windowY = win.getY();
                            dragging = true;
                            ui.run(function() {
                                dragHint.setTextColor(android.graphics.Color.parseColor("#ffffffff"));
                            });
                            return true;
                            
                        case android.view.MotionEvent.ACTION_MOVE:
                            if (dragging) {
                                var dx = rawX - downX;
                                var dy = rawY - downY;
                                win.setPosition(windowX + dx, windowY + dy);
                            }
                            return true;
                            
                        case android.view.MotionEvent.ACTION_UP:
                        case android.view.MotionEvent.ACTION_CANCEL:
                            if (dragging) {
                                dragging = false;
                                ui.run(function() {
                                    dragHint.setTextColor(android.graphics.Color.parseColor("#66ffffff"));
                                });
                            }
                            return true;
                    }
                    return false;
                }
            }));
            log("拖拽监听设置成功");
        }
    } catch(e) {
        log("设置拖拽监听失败: " + e);
    }
}, 500);

// ------------------------ 屏幕边界检测（加入窗口有效性检查）-----------------------
function clampWindow() {
    if (!isWindowAlive) return;  // 窗口已关闭，不再操作
    try {
        var x = win.getX();
        var y = win.getY();
        var w = win.getWidth();
        var h = win.getHeight();
        var winWidth = device.width;
        var winHeight = device.height;
        x = Math.max(0, Math.min(x, winWidth - w));
        y = Math.max(0, Math.min(y, winHeight - h));
        win.setPosition(x, y);
    } catch(e) {
        // 如果发生异常，可能是窗口已销毁，标记为非活跃
        if (e.message && e.message.indexOf("not attached") >= 0) {
            isWindowAlive = false;
        }
    }
}
clampInterval = setInterval(clampWindow, 500);

// 初始化
reset();
updateStartPauseIcon();
toast("悬浮计时器已启动\n空白处拖拽，点击×关闭");

// 防止脚本退出
setInterval(function(){}, 1000);







