// /api/save-place.js
import { kv } from "@vercel/kv";

export default async function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ error: "Method Not Allowed" });
  }

  try {
    const { id, data } = req.body;

    if (!id || !data) {
      return res.status(400).json({ error: "Missing place ID or data" });
    }

    await kv.set(`place:${id}`, data);

    return res.status(200).json({ success: true });
  } catch (error) {
    console.error("KV save error:", error);
    return res.status(500).json({ error: "Failed to save to KV" });
  }
}
