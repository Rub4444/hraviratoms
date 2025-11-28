<template>
  <div class="flex items-center justify-center py-10">
    <div class="loader"></div>
  </div>
</template>

<style scoped>
.loader {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 4px solid #A3D9A5; /* leaf-soft */
  border-top-color: #4B8B4F; /* leaf */
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
