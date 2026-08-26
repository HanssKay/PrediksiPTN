import math
import json
from collections import Counter


class C45Algorithm:
    """
    Implementasi Algoritma C4.5 untuk Decision Tree
    Digunakan untuk prediksi kelulusan siswa SNBP
    """

    def __init__(self):
        self.tree = None
        self.steps = []

    def entropy(self, data):
        """
        Menghitung Entropy dari dataset
        Rumus: E(S) = -Σ(pi * log2(pi))
        """
        total = len(data)
        if total == 0:
            return 0

        counter = Counter([row['status'] for row in data])
        ent = 0.0
        for count in counter.values():
            p = count / total
            if p > 0:
                ent -= p * math.log2(p)
        return ent

    def information_gain(self, data, attribute):
        """
        Menghitung Information Gain untuk sebuah atribut
        Rumus: Gain(S, A) = E(S) - Σ(|Sv|/|S| * E(Sv))
        """
        total_entropy = self.entropy(data)
        total = len(data)

        # Kelompokkan data berdasarkan nilai atribut
        groups = {}
        for row in data:
            val = row[attribute]
            if val not in groups:
                groups[val] = []
            groups[val].append(row)

        # Hitung weighted entropy
        weighted_entropy = 0.0
        for val, group in groups.items():
            weighted_entropy += (len(group) / total) * self.entropy(group)

        return total_entropy - weighted_entropy

    def split_info(self, data, attribute):
        """
        Menghitung Split Information untuk sebuah atribut
        Rumus: SplitInfo(S, A) = -Σ(|Sv|/|S| * log2(|Sv|/|S|))
        """
        total = len(data)
        groups = {}
        for row in data:
            val = row[attribute]
            if val not in groups:
                groups[val] = []
            groups[val].append(row)

        si = 0.0
        for val, group in groups.items():
            p = len(group) / total
            if p > 0:
                si -= p * math.log2(p)

        return si

    def gain_ratio(self, data, attribute):
        """
        Menghitung Gain Ratio (fitur utama C4.5 vs ID3)
        Rumus: GainRatio(S, A) = Gain(S, A) / SplitInfo(S, A)
        """
        gain = self.information_gain(data, attribute)
        si = self.split_info(data, attribute)
        if si == 0:
            return 0
        return gain / si

    def build_tree(self, data, attributes, depth=0, parent_label="Root"):
        """
        Membangun pohon keputusan secara rekursif
        """
        statuses = [row['status'] for row in data]
        counter = Counter(statuses)

        # Basis 1: Semua data memiliki kelas yang sama
        if len(counter) == 1:
            return {"label": statuses[0], "count": len(data), "type": "leaf"}

        # Basis 2: Tidak ada atribut tersisa
        if not attributes:
            majority = counter.most_common(1)[0][0]
            return {"label": majority, "count": len(data), "type": "leaf"}

        # Basis 3: Data kosong
        if not data:
            return {"label": "TIDAK LOLOS", "count": 0, "type": "leaf"}

        # Hitung gain ratio untuk semua atribut
        total_entropy = self.entropy(data)
        attr_calculations = []

        for attr in attributes:
            gain = self.information_gain(data, attr)
            si = self.split_info(data, attr)
            gr = gain / si if si > 0 else 0

            # Detail per nilai atribut
            groups = {}
            for row in data:
                val = row[attr]
                if val not in groups:
                    groups[val] = []
                groups[val].append(row)

            values_detail = []
            for val, group in sorted(groups.items()):
                group_counter = Counter([r['status'] for r in group])
                values_detail.append({
                    "value": val,
                    "count": len(group),
                    "lolos": group_counter.get("LOLOS", 0),
                    "tidak_lolos": group_counter.get("TIDAK LOLOS", 0),
                    "entropy": round(self.entropy(group), 6)
                })

            attr_calculations.append({
                "name": attr,
                "gain": round(gain, 6),
                "split_info": round(si, 6),
                "gain_ratio": round(gr, 6),
                "values": values_detail
            })

        # Pilih atribut dengan Gain Ratio tertinggi
        best = max(attr_calculations, key=lambda x: x['gain_ratio'])
        best_attr = best['name']

        # Simpan langkah perhitungan
        step = {
            "node": parent_label,
            "level": depth,
            "total_entropy": round(total_entropy, 6),
            "total_samples": len(data),
            "class_distribution": dict(counter),
            "attributes": sorted(attr_calculations, key=lambda x: -x['gain_ratio']),
            "best_attribute": best_attr,
            "best_gain_ratio": best['gain_ratio']
        }
        self.steps.append(step)

        # Bangun node pohon
        tree = {
            "attribute": best_attr,
            "type": "node",
            "children": {}
        }

        # Split data berdasarkan atribut terbaik
        remaining_attrs = [a for a in attributes if a != best_attr]
        groups = {}
        for row in data:
            val = row[best_attr]
            if val not in groups:
                groups[val] = []
            groups[val].append(row)

        for val, group in sorted(groups.items()):
            child_label = f"{best_attr} = {val}"
            tree["children"][val] = self.build_tree(
                group, remaining_attrs, depth + 1, child_label
            )

        return tree

    def predict(self, sample, tree=None):
        """Prediksi satu sample menggunakan pohon keputusan"""
        if tree is None:
            tree = self.tree

        if tree["type"] == "leaf":
            return tree["label"]

        attr = tree["attribute"]
        val = sample.get(attr, None)

        if val in tree["children"]:
            return self.predict(sample, tree["children"][val])
        else:
            return "TIDAK LOLOS"

    def tree_to_rules(self, tree=None, path=None):
        """Konversi pohon keputusan menjadi daftar aturan (rules)"""
        if tree is None:
            tree = self.tree
        if path is None:
            path = []

        rules = []

        if tree["type"] == "leaf":
            rule_str = " DAN ".join(path) + f" MAKA {tree['label']}" if path else tree["label"]
            rules.append({
                "conditions": list(path),
                "result": tree["label"],
                "rule_string": "JIKA " + rule_str if path else rule_str
            })
            return rules

        attr = tree["attribute"]
        for val, child in tree["children"].items():
            new_path = path + [f"{attr} = {val}"]
            rules.extend(self.tree_to_rules(child, new_path))

        return rules
