import { put } from "@vercel/blob";
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

    const { url } = await put(blobName, req, {
      access: "public",
      token: process.env.vercel_blob_rw_1gPJ4fw1FwfbjoBt_LOaJhewAgf1AWEghdFW7dcaGfOox1A, 
    });

    return res.status(200).json({ url });
  } catch (err) {
    console.error("Upload error:", err);
    return res.status(500).json({ error: "Blob upload failed", details: err.message });
  }
}
