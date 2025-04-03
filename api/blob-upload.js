// /api/blob-upload.js
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

    const token = process.env.BLOB_READ_WRITE_TOKEN;
    if (!token) {
      console.error("❌ Missing BLOB_READ_WRITE_TOKEN in environment.");
      return res.status(500).json({ error: "Missing blob token" });
    }

    const { url } = await put(blobName, req, {
      access: "public",
      token: token
    });

    return res.status(200).json({ url });
  } catch (err) {
    console.error("Upload error:", err);
    return res.status(500).send("A server error occurred.");
  }
}
