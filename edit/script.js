document.addEventListener('DOMContentLoaded', function() {  
    // 确保 fileList 是一个数组  
    const fileList = Array.from(document.querySelectorAll('.file-item')); // 修正类名  
  
    fileList.forEach(fileItem => {  
        fileItem.addEventListener('click', (event) => {  
            const filename = event.target.dataset.filename;  
            const contentArea = document.getElementById('file-content');  
  
            // 清除内容区域并显示加载中的提示  
            contentArea.value = '加载中...';  
  
            // 使用 AJAX 请求获取文件内容  
            const xhr = new XMLHttpRequest();  
            xhr.open('GET', 'get.php?file=' + filename, true);  
  
            // 存储被点击的文件项，以便在回调中使用  
            const clickedFileItem = event.currentTarget; // 使用 event.currentTarget  
  
            xhr.onload = () => {  
                if (xhr.status === 200) {  
                    // 加载文件内容到文本区域  
                    contentArea.value = xhr.responseText;  
  
                    // 选中当前文件  
                    fileList.forEach(item => item.classList.remove('selected'));  
                    clickedFileItem.classList.add('selected'); // 使用 clickedFileItem  
  
                    // 隐藏重命名按钮  
                    /*const renameBtn = clickedFileItem.querySelector('.rename-btn');  
                    if (renameBtn) {  
                        renameBtn.style.display = 'none';  
                    }  */
                }  
            };  
  
            xhr.onerror = () => {  
                contentArea.value = '加载文件时出错';  
            };  
  
            xhr.send();  
        });  
    });  
      
    // 新建文件  
    const newFileBtn = document.getElementById('new-file-btn');  
    newFileBtn.addEventListener('click', function() {  
        const newFileName = prompt('请输入新文件的名称：');  
        if (!newFileName) return; // 如果用户没有输入文件名，则不执行任何操作  
          
        // 创建一个新的空文件  
        const xhr = new XMLHttpRequest();  
        xhr.open('GET', 'new.php?file=' + newFileName, true);  
        xhr.onload = function() {  
            if (xhr.status === 200) {  
                // 文件创建成功，刷新文件列表  
                location.reload();  
            }  
        };  
        xhr.send();  
    });  
      
    // 保存文件  
    const saveBtn = document.getElementById('save-btn');  
    saveBtn.addEventListener('click', function() {  
        const filename = document.querySelector('#file-list .file-item.selected').dataset.filename;  
        const content = document.getElementById('file-content').value;  
          
        // 使用 AJAX 保存文件内容  
        const xhr = new XMLHttpRequest();  
        xhr.open('PUT', 'save.php?file=' + filename, true);  
        xhr.onload = function() {  
            if (xhr.status === 200) {  
                alert('文件保存成功！');  
            } else {  
                alert('保存文件时出错');  
            }  
        };  
        xhr.send(content);  
    });  
      
    // 重命名文件  
    document.addEventListener('click', function(event) {  
        const renameBtn = event.target.closest('.rename-btn');  
        if (!renameBtn) return; // 如果没有找到重命名按钮，则不执行任何操作  
          
        const filename = renameBtn.dataset.filename;  
        const newFileName = prompt('请输入新的文件名：', filename);  
        if (!newFileName) return; // 如果用户没有输入新文件名，则不执行任何操作  
          
        // 使用 AJAX 重命名文件  
        const xhr = new XMLHttpRequest();  
        xhr.open('POST', 'rename.php', true);  
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');  
        xhr.onload = function() {  
            if (xhr.status === 200) {  
                // 重命名成功，刷新文件列表  
                location.reload();  
            } else {  
                alert('重命名文件时出错');  
            }  
        };  
        xhr.send('old=' + encodeURIComponent(filename) + '&new=' + encodeURIComponent(newFileName));  
    });  
});