// /api/blob-upload.js
import { nanoid } from "nanoid";

export const config = {
  api: {
    bodyParser: false,
  },
};

export default async function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).send("Method not allowed");
  }

  try {
    const filename = req.headers["x-filename"];
    const extension = filename.split(".").pop();
    const blobName = `${filename.split(".")[0]}-${nanoid()}.${extension}`;

    const uploadRes = await fetch(`https://blob.vercel-storage.com/upload?slug=${blobName}&access=public`, {
      method: "POST",
      headers: {
        "Authorization": `Bearer vercel_blob_rw_1gPJ4fw1FwfbjoBt_LOaJhewAgf1AWEghdFW7dcaGfOox1A`
      },
      body: req
    });

    const data = await uploadRes.json();

    if (!uploadRes.ok) {
      throw new Error(data.error?.message || "Blob upload failed");
    }

    return res.status(200).json({ url: data.url });
  } catch (err) {
    console.error("Upload error:", err);
    return res.status(500).send("A server error occurred.");
  }
}
