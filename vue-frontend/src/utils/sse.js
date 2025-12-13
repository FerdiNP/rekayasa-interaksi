let controller = null;
let source = null;

export function startSSE(url, onMessage) {
  controller = new AbortController();
  source = new EventSource(url, { signal: controller.signal });

  source.onmessage = e => onMessage(JSON.parse(e.data));
  source.onerror = () => source.close();

  return source;
}

export function stopSSE() {
  if (controller) {
    controller.abort();     // menghentikan koneksi streaming
    controller = null;
    console.log("aborting");
  }
  if (source) {
    source.close();        // benar-benar close
    source = null;
    console.log("closing");
  }
}
