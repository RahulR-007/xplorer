fetch("https://xplorer-ashy.vercel.app/api/blob-test", {
  method: "POST"
})
  .then(res => res.json())
  .then(console.log)
  .catch(console.error);
