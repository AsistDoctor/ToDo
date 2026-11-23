<script setup lang="ts">
interface Task {
  id: number
  title: string
  done: boolean
}

const { data: tasks, error: loadError, refresh } = await useFetch<Task[]>('/api/tasks')
const newTitle = ref('')
const error = ref('')

// Отправляет изменение и заново забирает список: экран всегда показывает то, что лежит в базе
async function change(path: string, method: 'POST' | 'PUT' | 'DELETE', body?: object) {
  error.value = ''
  try {
    await $fetch(path, { method, body })
  } catch (e: any) {
    error.value = e.data?.message ?? 'Сервер недоступен. Запущен ли Laravel?'
  }
  await refresh()
  return !error.value
}

async function retry() {
  error.value = ''
  await refresh()
}

async function addTask() {
  if (await change('/api/tasks', 'POST', { title: newTitle.value.trim() })) newTitle.value = ''
}

// Галку переключает сам браузер; если сервер не принял изменение, возвращаем её на место
async function toggleTask(task: Task, event: Event) {
  const input = event.target as HTMLInputElement
  if (!(await change(`/api/tasks/${task.id}`, 'PUT', { done: input.checked }))) input.checked = !input.checked
}

function deleteTask(task: Task) {
  return change(`/api/tasks/${task.id}`, 'DELETE')
}
</script>

<template>
  <main class="wrap">
    <h1>Задачи</h1>

    <form @submit.prevent="addTask">
      <input v-model="newTitle" type="text" placeholder="Что нужно сделать?" aria-label="Новая задача" />
      <button class="add" :disabled="!newTitle.trim()">Добавить</button>
    </form>

    <p v-if="error || loadError" class="error">
      {{ error || 'Не удалось загрузить задачи. Запущен ли Laravel?' }}
      <button @click="retry()">Повторить</button>
    </p>
    <ul v-if="tasks?.length">
      <li v-for="task in tasks" :key="task.id" :class="{ done: task.done }">
        <label>
          <input type="checkbox" :checked="task.done" @change="toggleTask(task, $event)" />
          <span>{{ task.title }}</span>
        </label>
        <button class="del" aria-label="Удалить" @click="deleteTask(task)">×</button>
      </li>
    </ul>
    <p v-else-if="!loadError" class="empty">Задач пока нет — добавьте первую.</p>
  </main>
</template>

<style>
body {
  margin: 0;
  background: #f4f4f5;
  color: #18181b;
  font-family: system-ui, sans-serif;
}
.wrap {
  max-width: 520px;
  margin: 10vh auto;
  padding: 24px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
}
h1 {
  margin: 0 0 16px;
  font-size: 24px;
}
form {
  display: flex;
  gap: 8px;
}
input[type='text'] {
  flex: 1;
  padding: 10px 12px;
  border: 1px solid #d4d4d8;
  border-radius: 8px;
  font: inherit;
}
.add {
  padding: 10px 16px;
  border: 0;
  border-radius: 8px;
  background: #2563eb;
  color: #fff;
  font: inherit;
  cursor: pointer;
}
.add:disabled {
  opacity: 0.5;
  cursor: default;
}
.error {
  color: #dc2626;
}
.error button {
  margin-left: 8px;
}
.empty {
  margin: 16px 0 0;
  color: #71717a;
}
ul {
  margin: 8px 0 0;
  padding: 0;
  list-style: none;
}
li {
  display: flex;
  align-items: center;
  padding: 10px 0;
  border-top: 1px solid #e4e4e7;
}
label {
  display: flex;
  flex: 1;
  align-items: center;
  gap: 10px;
  cursor: pointer;
}
.done span {
  color: #a1a1aa;
  text-decoration: line-through;
}
.del {
  border: 0;
  background: none;
  color: #dc2626;
  font-size: 20px;
  cursor: pointer;
}
</style>
