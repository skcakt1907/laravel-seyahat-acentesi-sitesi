// Kanban Board JavaScript
// boardId ve csrfToken HTML'den data attribute olarak alınacak

function initKanban() {
  const boardElement = document.getElementById('kanbanBoard') || document.getElementById('kanbanLists');
  if (!boardElement) {
    console.error('Kanban board elementi bulunamadı!');
    return;
  }

  const boardId = boardElement.dataset.boardId;
  if (!boardId) {
    console.error('Board ID bulunamadı!');
    return;
  }
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  if (!csrfToken) {
    console.warn('CSRF token bulunamadı!');
  }

  // Liste sıralaması
  const listsContainer = document.getElementById('kanbanLists');
  new Sortable(listsContainer, {
    animation: 150,
    handle: '.kanban-list-header',
    onEnd: function(evt) {
      const listIds = Array.from(listsContainer.children).map(el => el.dataset.listId);
      fetch(`/admin/crm/kanban/${boardId}/reorder-lists`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ list_ids: listIds })
      });
    }
  });

  // Her liste için kart sıralaması
  document.querySelectorAll('.kanban-list-body').forEach(listBody => {
    new Sortable(listBody, {
      group: 'cards',
      animation: 150,
      onEnd: function(evt) {
        const cardId = evt.item.dataset.cardId;
        const newListId = evt.to.dataset.listId;
        const newPosition = Array.from(evt.to.children).indexOf(evt.item);
        
        fetch(`/admin/crm/kanban/${boardId}/move-card`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            card_id: cardId,
            new_list_id: newListId,
            new_position: newPosition
          })
        }).then(() => {
          location.reload();
        });
      }
    });
  });

  // Yeni Liste
  const addListForm = document.getElementById('addListForm');
  if (addListForm) {
    addListForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      
      // Form verilerini kontrol et
      const adi = formData.get('adi');
      if(!adi || adi.trim() === '') {
        alert('Liste adı gereklidir!');
        return;
      }
      
      fetch(`/admin/crm/kanban/${boardId}/lists`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        body: formData
      })
      .then(res => {
        if(!res.ok) {
          throw new Error('HTTP error! status: ' + res.status);
        }
        return res.json();
      })
      .then(data => {
        if(data.success) {
          $('#addListModal').modal('hide');
          location.reload();
        } else {
          alert('Liste eklenirken bir hata oluştu: ' + (data.message || 'Bilinmeyen hata'));
        }
      })
      .catch(error => {
        console.error('Liste ekleme hatası:', error);
        alert('Liste eklenirken bir hata oluştu. Lütfen tekrar deneyin.');
      });
    });
  }

  // Inline Liste Ekleme
  window.showAddListInline = function() {
    const addButton = document.getElementById('addListButton');
    const addForm = document.getElementById('addListInlineForm');
    if (addButton && addForm) {
      addButton.style.display = 'none';
      addForm.style.display = 'flex';
      addForm.querySelector('input[name="adi"]').focus();
    }
  };

  window.hideAddListInline = function() {
    const addButton = document.getElementById('addListButton');
    const addForm = document.getElementById('addListInlineForm');
    if (addButton && addForm) {
      addButton.style.display = 'flex';
      addForm.style.display = 'none';
      addForm.reset();
    }
  };

  // Inline Liste Ekleme Formu
  const addListInlineForm = document.getElementById('addListInlineFormElement');
  if (addListInlineForm) {
    // Renk seçimi için event listener
    const colorOptions = addListInlineForm.querySelectorAll('.color-option');
    const colorInput = addListInlineForm.querySelector('input[name="renk"]');
    
    colorOptions.forEach(option => {
      option.addEventListener('click', function() {
        const color = this.dataset.color;
        if (colorInput) {
          colorInput.value = color;
        }
        // Seçili rengi göster
        colorOptions.forEach(opt => opt.style.border = '2px solid #ddd');
        this.style.border = '3px solid #007bff';
      });
    });
    
    addListInlineForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      
      const adi = formData.get('adi');
      if(!adi || adi.trim() === '') {
        alert('Kategori adı gereklidir!');
        return;
      }
      
      fetch(`/admin/crm/kanban/${boardId}/lists`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        body: formData
      })
      .then(res => {
        if(!res.ok) {
          throw new Error('HTTP error! status: ' + res.status);
        }
        return res.json();
      })
      .then(data => {
        if(data.success) {
          hideAddListInline();
          location.reload();
        } else {
          alert('Kategori eklenirken bir hata oluştu: ' + (data.message || 'Bilinmeyen hata'));
        }
      })
      .catch(error => {
        console.error('Kategori ekleme hatası:', error);
        alert('Kategori eklenirken bir hata oluştu. Lütfen tekrar deneyin.');
      });
    });
  }

  // Liste Düzenle
  window.editList = function(id, adi, aciklama, renk) {
    const boardRenk = boardElement.dataset.boardRenk || '#007bff';
    
    // Modal'ı doldur
    document.getElementById('editListId').value = id;
    document.getElementById('editListAdi').value = adi;
    document.getElementById('editListAciklama').value = aciklama || '';
    document.getElementById('editListRenk').value = renk || boardRenk;
    
    // Modal'ı göster
    $('#editListModal').modal('show');
  };
  
  // Liste Düzenle Form Submit
  const editListForm = document.getElementById('editListForm');
  if (editListForm) {
    editListForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const listId = formData.get('list_id');
      
      formData.append('_method', 'PUT');
      
      fetch(`/admin/crm/kanban/${boardId}/lists/${listId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        body: formData
      }).then(res => res.json()).then(data => {
        if(data.success) {
          $('#editListModal').modal('hide');
          location.reload();
        }
      });
    });
  }
  
  // Renk paleti seçimi (bir kere event listener ekle)
  $(document).on('click', '.color-option', function() {
    const color = this.dataset.color;
    document.getElementById('editListRenk').value = color;
    
    // Seçili rengi göster
    document.querySelectorAll('.color-option').forEach(opt => {
      opt.style.border = '2px solid #ddd';
    });
    this.style.border = '3px solid #333';
  });
  
  // Modal açıldığında mevcut rengi işaretle
  $('#editListModal').on('shown.bs.modal', function() {
    const currentColor = document.getElementById('editListRenk').value;
    
    // Mevcut rengi işaretle
    document.querySelectorAll('.color-option').forEach(opt => {
      opt.style.border = '2px solid #ddd';
      if(opt.dataset.color.toLowerCase() === currentColor.toLowerCase()) {
        opt.style.border = '3px solid #333';
      }
    });
  });

  // Liste Sil
  window.deleteList = function(id) {
    if(!confirm('Bu listeyi silmek istediğinize emin misiniz? Tüm kartlar silinecek.')) return;
    
    fetch(`/admin/crm/kanban/${boardId}/lists/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    }).then(res => res.json()).then(data => {
      if(data.success) {
        location.reload();
      }
    });
  };

  // Kart Ekle
  window.addCard = function(listId) {
    document.getElementById('cardModalTitle').textContent = 'Yeni Kart';
    document.getElementById('cardForm').reset();
    document.getElementById('cardListId').value = listId;
    document.getElementById('cardId').value = '';
    $('#cardModal').modal('show');
  };

  // Kart Düzenle
  window.editCard = function(cardId) {
    // Kart bilgilerini al ve formu doldur
    const card = document.querySelector(`[data-card-id="${cardId}"]`);
    // Burada kart bilgilerini API'den alabilirsiniz veya data attribute'ları kullanabilirsiniz
    $('#cardModal').modal('show');
  };

  // Kart Form Submit
  const cardForm = document.getElementById('cardForm');
  if (cardForm) {
    cardForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const listId = formData.get('list_id');
      const cardId = formData.get('card_id');
      
      const url = cardId 
        ? `/admin/crm/kanban/${boardId}/lists/${listId}/cards/${cardId}`
        : `/admin/crm/kanban/${boardId}/lists/${listId}/cards`;
      
      if(cardId) {
        formData.append('_method', 'PUT');
      }
      
      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        body: formData
      }).then(res => res.json()).then(data => {
        if(data.success) {
          $('#cardModal').modal('hide');
          location.reload();
        }
      });
    });
  }
}

// Trello Benzeri Kart Detay Modal
let currentBoardId = null;
let currentCardId = null;

window.openCardModal = function(boardId, cardId) {
  currentBoardId = boardId;
  currentCardId = cardId;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  
  fetch(`/admin/crm/kanban/${boardId}/cards/${cardId}`, {
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  })
  .then(res => res.json())
  .then(data => {
    if(data.success) {
      const card = data.card;
      
      // Başlık ve liste
      document.getElementById('cardDetailTitle').textContent = card.baslik;
      document.getElementById('cardDetailBaslik').textContent = card.baslik;
      document.getElementById('cardDetailListName').textContent = card.list?.adi || '';
      
      // Açıklama
      document.getElementById('cardDetailAciklama').textContent = card.aciklama || '';
      
      // Atanan, Öncelik, Son Tarih, Durum
      document.getElementById('cardDetailAtanan').value = card.atanan_id || '';
      document.getElementById('cardDetailOncelik').value = card.oncelik || 'normal';
      document.getElementById('cardDetailSonTarih').value = card.son_tarih || '';
      document.getElementById('cardDetailDurum').value = card.durum || 'aktif';
      
      // Üyeler
      renderMembers(card.members || []);
      
      // Etiketler
      renderLabels(card.etiketler || []);
      
      // Checklist
      renderChecklists(card.checklists || []);
      
      // Ekler
      renderAttachments(card.attachments || []);
      
      // Yorumlar
      renderComments(card.comments || []);
      
      $('#cardDetailModal').modal('show');
    }
  });
};

function renderMembers(members) {
  const container = document.getElementById('cardMembersContainer');
  container.innerHTML = '';
  
  members.forEach(member => {
    const yonetici = member.yonetici || member;
    const avatar = document.createElement('span');
    avatar.className = 'kanban-member-avatar';
    avatar.style.cssText = 'width: 32px; height: 32px; border-radius: 50%; background: #3498db; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 5px; cursor: pointer;';
    avatar.textContent = (yonetici.adi || yonetici.kullaniciadi || 'U').charAt(0).toUpperCase();
    avatar.title = yonetici.adi || yonetici.kullaniciadi;
    avatar.onclick = () => toggleMember(yonetici.id);
    container.appendChild(avatar);
  });
}

function renderLabels(labels) {
  const container = document.getElementById('cardLabelsContainer');
  container.innerHTML = '';
  
  if(!Array.isArray(labels)) return;
  
  labels.forEach((label, index) => {
    const span = document.createElement('span');
    span.className = 'kanban-label';
    span.style.cssText = `background-color: ${label.renk || '#3498db'}; color: white; padding: 4px 8px; border-radius: 3px; margin-right: 5px; margin-bottom: 5px; display: inline-block; font-size: 12px;`;
    span.textContent = label.adi || '';
    container.appendChild(span);
  });
}

function renderChecklists(checklists) {
  const container = document.getElementById('cardChecklistsContainer');
  container.innerHTML = '';
  
  checklists.forEach(checklist => {
    const checklistDiv = document.createElement('div');
    checklistDiv.className = 'card-checklist mb-3';
    checklistDiv.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">${checklist.baslik}</h6>
        <button type="button" class="btn btn-sm btn-link text-danger" onclick="deleteChecklist(${checklist.id})">
          <i class="mdi mdi-trash-can"></i>
        </button>
      </div>
      <div class="checklist-items" id="checklist-items-${checklist.id}"></div>
      <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addChecklistItem(${checklist.id})">
        <i class="mdi mdi-plus"></i> Madde Ekle
      </button>
    `;
    container.appendChild(checklistDiv);
    
    // Checklist items
    const itemsContainer = document.getElementById(`checklist-items-${checklist.id}`);
    (checklist.items || []).forEach(item => {
      const itemDiv = document.createElement('div');
      itemDiv.className = 'form-check mb-2';
      itemDiv.innerHTML = `
        <input class="form-check-input" type="checkbox" ${item.tamamlandi ? 'checked' : ''} 
               onchange="toggleChecklistItem(${checklist.id}, ${item.id})">
        <label class="form-check-label ${item.tamamlandi ? 'text-decoration-line-through text-muted' : ''}">
          ${item.metin}
        </label>
      `;
      itemsContainer.appendChild(itemDiv);
    });
  });
}

function renderAttachments(attachments) {
  const container = document.getElementById('cardAttachmentsContainer');
  container.innerHTML = '';
  
  attachments.forEach(attachment => {
    const div = document.createElement('div');
    div.className = 'card-attachment mb-2 p-2 border rounded';
    div.innerHTML = `
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <i class="mdi mdi-file"></i> 
          <a href="/storage/${attachment.dosya_yolu}" target="_blank">${attachment.dosya_adi}</a>
          <small class="text-muted">(${attachment.dosya_boyutu_format || 'N/A'})</small>
        </div>
        <button type="button" class="btn btn-sm btn-link text-danger" onclick="deleteAttachment(${attachment.id})">
          <i class="mdi mdi-trash-can"></i>
        </button>
      </div>
    `;
    container.appendChild(div);
  });
}

function renderComments(comments) {
  const container = document.getElementById('cardCommentsContainer');
  container.innerHTML = '';
  
  comments.forEach(comment => {
    const div = document.createElement('div');
    div.className = 'card-comment mb-3 p-3 border rounded';
    const yazar = comment.yazar || {};
    div.innerHTML = `
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <strong>${yazar.adi || yazar.kullaniciadi || 'Bilinmeyen'}</strong>
          <small class="text-muted ml-2">${new Date(comment.created_at).toLocaleString('tr-TR')}</small>
        </div>
        <button type="button" class="btn btn-sm btn-link text-danger" onclick="deleteComment(${comment.id})">
          <i class="mdi mdi-trash-can"></i>
        </button>
      </div>
      <p class="mb-0">${comment.yorum}</p>
    `;
    container.appendChild(div);
  });
}

window.updateCardField = function(field, value) {
  if(!currentBoardId || !currentCardId) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const listId = document.querySelector(`[data-card-id="${currentCardId}"]`)?.closest('.kanban-list')?.dataset.listId;
  
  if(!listId) return;
  
  const formData = new FormData();
  formData.append(field, value);
  formData.append('_method', 'PUT');
  
  fetch(`/admin/crm/kanban/${currentBoardId}/lists/${listId}/cards/${currentCardId}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  }).then(res => res.json()).then(data => {
    if(data.success) {
      // Başlık güncellendi
      if(field === 'baslik') {
        document.getElementById('cardDetailTitle').textContent = value;
      }
    }
  });
};

window.addComment = function() {
  const text = document.getElementById('newCommentText').value.trim();
  if(!text) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const formData = new FormData();
  formData.append('yorum', text);
  
  fetch(`/admin/crm/kanban/${currentBoardId}/cards/${currentCardId}/comments`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  }).then(res => res.json()).then(data => {
    if(data.success) {
      document.getElementById('newCommentText').value = '';
      openCardModal(currentBoardId, currentCardId); // Yeniden yükle
    }
  });
};

window.deleteComment = function(commentId) {
  if(!confirm('Yorumu silmek istediğinize emin misiniz?')) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  fetch(`/admin/crm/kanban/${currentBoardId}/cards/${currentCardId}/comments/${commentId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  }).then(res => res.json()).then(data => {
    if(data.success) {
      openCardModal(currentBoardId, currentCardId);
    }
  });
};

window.addChecklist = function() {
  const baslik = prompt('Checklist Başlığı:');
  if(!baslik) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const formData = new FormData();
  formData.append('baslik', baslik);
  
  fetch(`/admin/crm/kanban/${currentBoardId}/cards/${currentCardId}/checklists`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  }).then(res => res.json()).then(data => {
    if(data.success) {
      openCardModal(currentBoardId, currentCardId);
    }
  });
};

window.addChecklistItem = function(checklistId) {
  const metin = prompt('Madde:');
  if(!metin) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const formData = new FormData();
  formData.append('metin', metin);
  
  fetch(`/admin/crm/kanban/${currentBoardId}/cards/${currentCardId}/checklists/${checklistId}/items`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  }).then(res => res.json()).then(data => {
    if(data.success) {
      openCardModal(currentBoardId, currentCardId);
    }
  });
};

window.toggleChecklistItem = function(checklistId, itemId) {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  fetch(`/admin/crm/kanban/${currentBoardId}/cards/${currentCardId}/checklists/${checklistId}/items/${itemId}/toggle`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  }).then(res => res.json()).then(data => {
    if(data.success) {
      openCardModal(currentBoardId, currentCardId);
    }
  });
};

window.toggleMember = function(yoneticiId) {
  if(!yoneticiId) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const formData = new FormData();
  formData.append('yonetici_id', yoneticiId);
  
  fetch(`/admin/crm/kanban/${currentBoardId}/cards/${currentCardId}/members/toggle`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  }).then(res => res.json()).then(data => {
    if(data.success) {
      openCardModal(currentBoardId, currentCardId);
    }
  });
};

window.deleteCard = function() {
  if(!confirm('Kartı silmek istediğinize emin misiniz?')) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const listId = document.querySelector(`[data-card-id="${currentCardId}"]`)?.closest('.kanban-list')?.dataset.listId;
  
  fetch(`/admin/crm/kanban/${currentBoardId}/lists/${listId}/cards/${currentCardId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  }).then(res => res.json()).then(data => {
    if(data.success) {
      $('#cardDetailModal').modal('hide');
      location.reload();
    }
  });
};

window.deleteChecklist = function(checklistId) {
  if(!confirm('Checklist\'i silmek istediğinize emin misiniz?')) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  fetch(`/admin/crm/kanban/${currentBoardId}/cards/${currentCardId}/checklists/${checklistId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  }).then(res => res.json()).then(data => {
    if(data.success) {
      openCardModal(currentBoardId, currentCardId);
    }
  });
};

window.deleteAttachment = function(attachmentId) {
  if(!confirm('Dosyayı silmek istediğinize emin misiniz?')) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  fetch(`/admin/crm/kanban/${currentBoardId}/cards/${currentCardId}/attachments/${attachmentId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  }).then(res => res.json()).then(data => {
    if(data.success) {
      openCardModal(currentBoardId, currentCardId);
    }
  });
};

window.showLabelEditor = function() {
  const adi = prompt('Etiket Adı:');
  if(!adi) return;
  
  const renk = prompt('Renk (hex, örn: #3498db):', '#3498db');
  if(!renk) return;
  
  // Etiketleri güncelle (mevcut etiketler + yeni)
  const currentLabels = JSON.parse(document.getElementById('cardLabelsContainer').dataset.labels || '[]');
  currentLabels.push({ adi, renk });
  
  updateCardField('etiketler', JSON.stringify(currentLabels));
  setTimeout(() => openCardModal(currentBoardId, currentCardId), 500);
};

// Attachment form
document.addEventListener('DOMContentLoaded', function() {
  const attachmentForm = document.getElementById('attachmentForm');
  if(attachmentForm) {
    attachmentForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
      
      fetch(`/admin/crm/kanban/${currentBoardId}/cards/${currentCardId}/attachments`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        body: formData
      }).then(res => res.json()).then(data => {
        if(data.success) {
          this.reset();
          openCardModal(currentBoardId, currentCardId);
        }
      });
    });
  }
});

window.addBoardMember = function() {
  const yoneticiId = document.getElementById('newBoardMemberSelect').value;
  const rol = document.getElementById('newBoardMemberRole').value;
  
  if(!yoneticiId) {
    alert('Lütfen bir üye seçin.');
    return;
  }
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const formData = new FormData();
  formData.append('yonetici_id', yoneticiId);
  formData.append('rol', rol);
  
  fetch(`/admin/crm/kanban/${currentBoardId || document.getElementById('kanbanLists')?.dataset.boardId}/members/toggle`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  }).then(res => res.json()).then(data => {
    if(data.success) {
      location.reload();
    } else {
      alert(data.message || 'Hata oluştu.');
    }
  });
};

window.removeBoardMember = function(yoneticiId) {
  if(!confirm('Bu üyeyi board\'dan kaldırmak istediğinize emin misiniz?')) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const formData = new FormData();
  formData.append('yonetici_id', yoneticiId);
  
  fetch(`/admin/crm/kanban/${currentBoardId || document.getElementById('kanbanLists')?.dataset.boardId}/members/toggle`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  }).then(res => res.json()).then(data => {
    if(data.success) {
      location.reload();
    }
  });
};

// Sayfa yüklendiğinde başlat
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initKanban);
} else {
  initKanban();
}

